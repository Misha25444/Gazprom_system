<?php
 function translateEnglishToRussianMonths($dateString) {
  $months = array(
      'January' => 'января',
      'February' => 'февраля',
      'March' => 'марта',
      'April' => 'апреля',
      'May' => 'мая',
      'June' => 'июня',
      'July' => 'июля',
      'August' => 'августа',
      'September' => 'сентября',
      'October' => 'октября',
      'November' => 'ноября',
      'December' => 'декабря'
  );
  $dateTimeParts = explode(' ', $dateString);
  $dateParts = explode('-', $dateTimeParts[0]);
  $dateParts[1] = $months[date('F', strtotime($dateTimeParts[0]))];
  $translatedDate = $dateParts[2] . ' ' . $dateParts[1] . ' ' . $dateParts[0];
  if (isset($dateTimeParts[1])) {
      $translatedDate .= ' ' . $dateTimeParts[1];
  }
  return $translatedDate;
}

?>