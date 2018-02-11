<!DOCTYPE html>
<html lang="ru-RU">
<head>
  <meta charset="utf-8">
</head>
<body>
<h2>Пользователь успешно прошёл тест по занятию "{{ $lesson_title }}"</h2>

<div>
  Данные пользователя:
  <ol>
    <li>Имя: {{ $first_name }}</li>
    <li>Фамилия: {{ $second_name }}</li>
    <li>Отчество: {{ $surname }}</li>
    <li>Email: {{ $email }}</li>
    <li>Телефон: {{ $phone }}</li>
  </ol>
</div>

<div>
  Информация по тесту:
  <ol>
    <li>Занятие: <a href="{{ $lesson_link }}">{{ $lesson_title }}</a></li>
    <li>Курс: <a href="{{ $course_link }}">{{ $course_title }}</a></li>
    <li>Результат: {{ $right_answers }}/{{ $total_questions_count }}</li>
    <li>Процент успеха: {{ $success_percentage }}%</li>
  </ol>
</div>

</body>
</html>