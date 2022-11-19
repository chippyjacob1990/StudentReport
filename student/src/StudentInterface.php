<?php

namespace Drupal\student;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a student entity type.
 */
interface StudentInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the student rollnumber.
   *
   * @return string
   *   Rollnumber of the student.
   */
  public function getRollNo();

  /**
   * Sets the student rollnumber.
   *
   * @param int $rollno
   *   The student rollnumber.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setRollNo($rollno);

  /**
   * Gets the student name.
   *
   * @return string
   *   Name of the student.
   */
  public function getName();

  /**
   * Sets the student name.
   *
   * @param string $name
   *   The student name.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setName($name);

  /**
   * Gets the student name.
   *
   * @return string
   *   Class of the student.
   */
  public function getClass();

  /**
   * Sets the student class.
   *
   * @param string $class
   *   The student class.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setClass($class);

  /**
   * Gets the student's contact number.
   *
   * @return string
   *   Contact number of the student.
   */
  public function getContactNumber();

  /**
   * Sets the student's contact number.
   *
   * @param string $contact_number
   *   The student's contact number.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setContactNumber($contact_number);

  /**
   * Gets the student creation timestamp.
   *
   * @return int
   *   Creation timestamp of the student.
   */
  public function getCreatedTime();

  /**
   * Sets the student creation timestamp.
   *
   * @param int $timestamp
   *   The student creation timestamp.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the student status.
   *
   * @return bool
   *   TRUE if the student is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the student status.
   *
   * @param bool $status
   *   TRUE to enable this student, FALSE to disable.
   *
   * @return \Drupal\student\StudentInterface
   *   The called student entity.
   */
  public function setStatus($status);

}
