<?php $user_p=auth()->user()->password;
?>
@component('mail::message')
    # Здравствуйте

Вы зарегестрироволись на сайте <a href="http://muhachev.site">Форум </a> .

@component('mail::button', ['url' => 'http://muhachev.site/home?token='.$user_p])
    Подтвердить регистрацию
@endcomponent

@endcomponent
