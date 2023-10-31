<?php

$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

foreach ($example_persons_array as $person) {

    $fullname = $person['fullname'];
    $job = $person['job'];
    
    $parts = explode(" ", $fullname);
    $surname = $parts[0];
    $name = $parts[1];
    $patronymic = $parts[2];

    $fullName = getFullnameFromParts($surname, $name, $patronymic);
    echo $fullName . '</br>';
    
}

echo '</br>';

foreach ($example_persons_array as $person) {

    $fullname = $person['fullname'];
    $job = $person['job'];
    
    $shortName = getShortName($fullname);
    echo $shortName . '</br>';
}

function getFullnameFromParts($surname, $name, $patronymic) {
    return "$surname $name $patronymic";
}

function getPerfectPartner($surname, $name, $patronymic, $persons) {

    $surname = mb_convert_case($surname, MB_CASE_TITLE);
    $name = mb_convert_case($name, MB_CASE_TITLE);
    $patronymic = mb_convert_case($patronymic, MB_CASE_TITLE);
    
    $fullname = getFullnameFromParts($surname, $name, $patronymic);
    $gender = getGenderFromName($fullname);
    
   do {
        $randomPerson = $persons[array_rand($persons)];
        $randomFullname = $randomPerson['fullname'];
        $randomGender = getGenderFromName($randomFullname);
    } while ($gender === $randomGender || $gender === 0 || $randomGender === 0);
    
    $procent = mt_rand(50, 100) / 100;
    
    $result = $fullname . ' + ' . $randomFullname . ' = ';
    $result .= '♡ Идеально на ' . number_format($procent * 100, 2) . '% ♡';
    
    return $result;
}

function getShortName($fullname) {
    
    $parts = getPartsFromFullname($fullname);
    
    $surname = $parts['surname'];
    $name = $parts['name'];
    $patronymic = $parts['patronymic'];
    
    $shortSurname = mb_substr($surname, 0, 1, 'UTF-8');
    return "$name $shortSurname.";
}

function getPartsFromFullname($fullname) {
    $parts = explode(" ", $fullname);
    return [
        'surname' => $parts[0],
        'name' => $parts[1],
        'patronymic' => $parts[2]
    ];
}

function getGenderFromName($fullname) {

    $parts = getPartsFromFullname($fullname);

    $surname = $parts['surname'];
    $name = $parts['name'];
    $patronymic = $parts['patronymic'];

    $genderSum = 0;
    
    // Women
     if (mb_substr($surname, -2) == 'ва') {
        $genderSum--;
    }
     if (mb_substr($name, -1) == 'а') {
        $genderSum--;
    }
    if (mb_substr($patronymic, -3) == 'вна') {
        $genderSum--;
    }
   
    // Men
        if (mb_substr($surname, -1) == 'в') {
        $genderSum++;
    }
        if (mb_substr($name, -1) == 'й' && mb_substr($name, -1) == 'н') {
        $genderSum++;
    }

    if (mb_substr($patronymic, -2) == 'ич') {
        $genderSum++;
    }
    
    if ($genderSum > 0) {
        return 1; 
    } elseif ($genderSum < 0) {
        return -1; 
    } else {
        return 0;
        
    }
}

function getGenderDescription($persons) {

    $total = count($persons); 

    $maleCount = 0;
    $femaleCount = 0; 
    $undefinedCount = 0; 
    
    foreach ($persons as $person) {

        $fullname = $person['fullname'];
        
        $gender = getGenderFromName($fullname);
        
        if ($gender > 0) {
            $maleCount++;
        } elseif ($gender < 0) {
            $femaleCount++;
        } else {
            $undefinedCount++;
        }
    }
    
    $malePercentage = round(($maleCount / $total) * 100, 1);
    $femalePercentage = round(($femaleCount / $total) * 100, 1);
    $undefinedPercentage = round(($undefinedCount / $total) * 100, 1);
    
    $genderDescription = "<b> Гендерный состав аудитории: </b>" . "<br>";
    $genderDescription .= "<b> ------------------------------- </b>"  . "<br>";
    $genderDescription .= "Мужчины - $malePercentage%" . "<br>";
    $genderDescription .= "Женщины - $femalePercentage%" . "<br>";
    $genderDescription .= "Не удалось определить - $undefinedPercentage%" . "<br>";
    
    return $genderDescription;
}

echo '</br>';
echo getGenderDescription($example_persons_array) . '</br>';
echo getPerfectPartner($surname, $name, $patronymic, $example_persons_array);