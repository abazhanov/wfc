
<?php
include 'template.php';
        
// Страница авторизации

# Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

# Соединямся с БД
$link=mysqli_connect("localhost", "root", "", "wfc");

if(isset($_POST['submit']))
{
    # Вытаскиваем из БД запись, у которой логин равняеться введенному
    $query = mysqli_query($link,"SELECT Login, Password FROM users WHERE Login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);

    # Сравниваем пароли
    if($data['Password'] === md5(md5($_POST['password'])))
    {
        # Генерируем случайное число и шифруем его
        $hash = md5(generateCode(10));

           # Записываем в БД новый хеш авторизации и IP
        mysqli_query($link, "UPDATE users SET hash='".$hash."' ".$insip." WHERE Login='".$data['Login']."'");

        # Ставим куки
        setcookie("id", $data['Login'], time()+60);
        setcookie("hash", $hash, time()+60);

        # Переадресовываем браузер на страницу проверки нашего скрипта
        header("Location: check.php"); exit();
    }
    else
    {
        print "Вы ввели неправильный логин/пароль";
        $index_auth="Вы ввели неправильный логин/пароль";
    }
}
?>
        
<div class="footer">
    <div>ЖДЕМ ПОСЫЛКУ ИЗ КИТАЯ</div>
    <div class="index_auth">
        <?$index_auth;?>
    </div>



</div>
<div class="main">
<form method="POST">
    <label for="login" class="index_label">Логин</label><input name="login" type="text" class="index_input" id="login"><br>
    <label for="password" class="index_label">Пароль</label><input name="password" type="password" class="index_input" id="password"><br>
    
    <input name="submit" type="submit" value="Войти" class="index_submit">
</form>
</div>
    
    