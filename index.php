<?php 
include 'array.php';
//getPartsFromFullname принимает как аргумент одну строку — склеенное ФИО. 
//Возвращает как результат массив из трёх элементов с ключами ‘name’, ‘surname’ и ‘patronomyc’.

function getPartsFromFullname($person) {
	$personName = explode(' ', $person);
	$fullName = [
		'surname' => $personName[0],
		'name' => $personName[1], 
		'patronomyc' => $personName[2],
	];
	return $fullName;
};

//getFullnameFromParts принимает как аргумент три строки — фамилию, имя и отчество. 
//Возвращает как результат их же, но склеенные через пробел.

function getFullnameFromParts($surname, $name, $patronomyc){
	$fullname = "";
	$fullname .= $surname;
	$fullname .= " ";
	$fullname .= $name;
	$fullname .= " ";
	$fullname .= $patronomyc;
	return $fullname;
};

//функция getShortName, принимающую как аргумент строку, содержащую ФИО вида «Иванов Иван Иванович» 
//и возвращающую строку вида «Иван И.», где сокращается фамилия и отбрасывается отчество. 

function getShortName($person){
	$shortname = "";
	$shortname .= getPartsFromFullname($person)['name'];
	$shortname .= " ";
	$shortname .= mb_substr(getPartsFromFullname($person)['surname'], 0, 1);
	$shortname .= ".";
	return $shortname;
};

//функция getGenderFromName, принимающую как аргумент строку, 
//содержащую ФИО (вида «Иванов Иван Иванович»).
function getGenderFromName($person){
	$gender = 0;
	$fullname = getPartsFromFullname($person);
	$searchName = mb_substr($fullname['name'], mb_strlen($fullname['name']) - 1);
	$searchSurnameFe = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 2);
	$searchSurnameMa = mb_substr($fullname['surname'], mb_strlen($fullname['surname']) - 1);
	$searchPatronomycFe = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 3);
	$searchPatronomycMa = mb_substr($fullname['patronomyc'], mb_strlen($fullname['patronomyc']) - 2);
	if (($searchName == 'й' || $searchName == 'н') || ($searchSurnameMa == 'в') || ($searchPatronomycMa == 'ич')) {
		$gender++;
	}elseif (($searchName == 'а') || ($searchSurnameFe == 'ва') || ($searchPatronomycFe == 'вна')) {
		$gender--;
	}
	if($gender > 0){
		$printGender = "мужской пол";
	}elseif ($gender < 0) {
		$printGender = "женский пол";
	}else {
		$printGender = "неопределенный пол";
	}
	return $printGender;
};

//функция getGenderDescription для определения полового состава аудитории

function getGenderDescription($arrayExample){
	for ($i=0; $i < count($arrayExample); $i++) { 
		$person = $arrayExample[$i]['fullname'];
		$gender[$i] = getGenderFromName($person);
		};
	$numbersMale = array_filter($gender, function($gender) {
   	return $gender == "мужской пол";
   });
	$numbersFemale = array_filter($gender, function($gender) {
   	return $gender == "женский пол";
   });
	$numbersOther = array_filter($gender, function($gender) {
   	return $gender == "неопределенный пол";
	});
	$resultMa = count($numbersMale)/count($arrayExample) * 100;
	$resultFe = count($numbersFemale)/count($arrayExample) * 100;
	$resultOth = count($numbersOther)/count($arrayExample) * 100;

	echo 'Гендерный состав аудитории: <hr>' . 'Мужчины - ' . round($resultMa, 2). '%<br>' . 'Женщины - ' . round($resultFe, 2) . '%<br>' . 'Не удалось определить - ' . round($resultOth, 2) . '%<br>';
};

//функция getPerfectPartner для определения «идеальной» пары.

function getPerfectPartner($surname, $name, $patronomyc, $arrayExample){
	$surnamePerson = mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
	$namePerson = mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
	$patronomycPerson = mb_convert_case($patronomyc, MB_CASE_TITLE_SIMPLE); 
	$fullname = getFullnameFromParts($surnamePerson, $namePerson, $patronomycPerson);
	$genderPerson = getGenderFromName($fullname);
	$numberRand = rand(0, count($arrayExample)-1);
	$personTwo = $arrayExample[$numberRand]['fullname'];
	$genderPersonTwo = getGenderFromName($personTwo);
	if (($genderPerson == $genderPersonTwo) || ($genderPersonTwo == "неопределенный пол")) {
				$genderCompare = false;
				while ($genderCompare == false) {
					if (($genderPerson != $genderPersonTwo) && ($genderPersonTwo != "неопределенный пол")) {
						$genderCompare = true;
						$randomNumber = rand(5000, 10000)/100;
						$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
					   echo $text;
		   		};
					$numberRand = rand(0, count($arrayExample)-1);
					$personTwo = $arrayExample[$numberRand]['fullname'];
					$genderPersonTwo = getGenderFromName($personTwo);
		   	};
		}else {
			$randomNumber = rand(5000, 10000)/100;
			$text = getShortName($fullname) . ' + ' . getShortName($personTwo) . ' = <br>' . "♡ Идеально на {$randomNumber}% ♡";
		   echo $text;
		};
};

echo "<br>Первое задание: <br>";
print_r(getFullnameFromParts("Иванов", "Иван", "Иванович") . "<br>");
echo "<br>Второе задание: <br>";
print_r(getPartsFromFullname("Иванов Иван Иванович"));
echo "<br><br>Третье задание: <br>";
print_r(getShortName("Иванов Иван Иванович") . "<br>");
echo "<br>Четвертое задание: <br>";
print_r(getGenderFromName("Иванов Иван Иванович") . "<br>");
echo "<br>Пятое задание: <br>";
getGenderDescription($example_persons_array);
echo "<br>Шестое задание: <br>";
getPerfectPartner("ИВАнов", "ИВАН", "ИваНович", $example_persons_array);
?>