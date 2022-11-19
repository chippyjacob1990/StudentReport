<?php

namespace Drupal\result\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\File\FileSystemInterface;

/**
 * Configuration form for setting upper and lower scores.
 */
class ExportForm extends FormBase {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs new ExportForm object.
   *
   * @param \Drupal\Core\Database\Connection $connection
   *   Database service.
   */
  
   /**
   * The file system service.
   *
   * @var \Drupal\Core\File\FileSystemInterface
   */
  protected $fileSystem;

  public function __construct(Connection $connection, FileSystemInterface $file_system) {
    $this->database = $connection;
    
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('file_system'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'score_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['settings'] = [
      '#markup' => $this->t('Download the results of students as an excel file.'),
    ];

    $form['upper_score'] = [
      '#type' => 'number',
      '#title' => $this->t('Upper Score'),
      '#description' => $this->t('Upper Score'),
    ];

    $form['lower_score'] = [
      '#type' => 'number',
      '#title' => $this->t('Lower Score'),
      '#description' => $this->t('Lower Score'),
    ];

    $form['subject'] = [
      '#type' => 'select',
      '#title' => $this->t('Subject'),
      '#description' => $this->t('Subject'),
      '#options' => [
        NULL => $this->t('-Select-'),
        'Biology' => $this->t('Biology'),
        'Geo' => $this->t('Geo'),
        'Math' => $this->t('Math'),
        'English' => $this->t('English'),
        'all' => $this->t('All'),
      ],
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Export'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $subject = $form_state->getValue('subject');
    $lower_score = $form_state->getValue('lower_score');
    $upper_score = $form_state->getValue('upper_score');

    $query = $this->database->select('student', 's');
    $query->join('result', 'r', 's.roll_no = r.roll_no');
    $query->fields('s', ['name', 'roll_no']);
    $query->fields('r', ['subject', 'score']);
    if ($subject != "all") {
      $query->condition('r.subject', $subject, '=');
    }
    $student_details = $query->execute()->fetchAll();

    foreach ($student_details as $row) {
      $data[] = [
        "Name" => $row->name,
        "Roll Number" => $row->roll_no,
        "Subject" => $row->subject,
        "Score" => $row->score,
      ];
    }

    // Excel file name for download.
    $fileName = "result_data-" . date('YmdHmi') . ".xls";

    // Headers for download.
    header("Content-Disposition: attachment; filename=\"$fileName\"");
    header("Content-Type: application/vnd.ms-excel");
    header('Content-Length: ' . filesize($fileName));
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');

    $flag = FALSE;
    foreach ($data as $row) {
      if (!$flag) {
        // Display column names as first row.
        echo implode("\t", array_keys($row)) . "\n";
        $flag = TRUE;
      }
      // Filter data.
      array_walk($row, 'filterData');
      $result_row = array_values($row);
      if ($result_row[3] > $upper_score) {
        $result_row[3] = '<span style="color:green">' . $result_row[3] . '</span>';
      }
      if ($result_row[3] < $lower_score) {
        $result_row[3] = '<span style="color:red">' . $result_row[3] . '</span>';
      }
      echo implode("\t", $result_row) . "\n";
    }
    $this->messenger()->addStatus("Downloaded the file.");
    exit;
  }

  /**
   * Filter the data.
   *
   * @param string $str
   *   String to process.
   */
  function filterData(&$str) {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
    if (strstr($str, '"')) {
      $str = '"' . str_replace('"', '""', $str) . '"';
    }
  }
}
