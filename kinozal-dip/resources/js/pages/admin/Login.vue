<script>
import axios from 'axios';

export default {
  name: 'Login',

  data() {
    return { // тут состояние 
    }  
  }, 
  methods: {
    // методы для бронирования 
    login() { 
      axios.post('/api/login', { 
        email: this.email, 
        password: this.password 
      }) 
      .then(response => { 
        // сохраняем токен 
        localStorage.setItem('token', response.data.token) 
        // перенаправление или иные действия 
      }) 
      .catch(error => { 
        console.error('Ошибка входа:', error) 
      }) 
    } 
  },
  mounted() {
    // fetch данных о зале 
    document.body.classList.add('page-admin');
    axios.get('http://127.0.0.1:8000/movies')
      .then(response => {
        console.log(response.data);
      })
      .catch(error => {
        console.error(error);
      });
  }
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторррская</span>
  </header>
  
  <main>
    <section class="login">
      <header class="login__header">
        <h2 class="login__title">Авторизация</h2>
      </header>
      <div class="login__wrapper">
        <form class="login__form" action="http://f0769682.xsph.ru/authorization.php" method="POST" accept-charset="utf-8">
          <label class="login__label" for="email">
            E-mail
            <input class="login__input" type="email" placeholder="example@domain.xyz" name="email" required>
          </label>
          <label class="login__label" for="pwd">
            Пароль
            <input class="login__input" type="password" placeholder="" name="password" required>
          </label>
          <div class="text-center">
            <input value="Авторизоваться" type="submit" class="login__button">
          </div>
        </form>
      </div>
    </section>
  </main>
</template>

<style>
@charset "UTF-8";

@keyframes slideFromTop {
  0% {
    top: -50vh;
  }

  100% {
    top: 100px;
  }
}

@keyframes darken {
  0% {
    background: 0;
  }

  100% {
    background: rgba(0, 0, 0, 0.7);
  }
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  font-family: "Roboto", sans-serif;
  font-size: 62.5%;
}

body.page-admin {
  background-image: url("../src/admin/background.jpg");
  background-color: rgba(0, 0, 0, 0.5);
  background-blend-mode: multiply;
  background-size: cover;
  background-attachment: fixed;
  counter-reset: num;
}

input[type='radio'],
input[type='submit'],
button, select {
  cursor: pointer;
}

.text-center {
  text-align: center;
}

.page-header {
  width: 972px;
  margin: 0 auto;
}

.page-header {
  padding: 35px 42px 35px 104px;
}

.page-header {
  color: #FFFFFF;
  text-transform: uppercase;
}

.page-header .page-header__title {
  margin: 0;
  font-weight: 900;
  font-size: 3.4rem;
}

.page-header .page-header__title span {
  font-weight: 100;
}

.page-header .page-header__subtitle {
  font-size: 1rem;
  letter-spacing: 0.46em;
}



.login__button {
  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.24), 0px 0px 3px rgba(0, 0, 0, 0.12);
  border-radius: 3px;
  border: none;
  background-color: #FFFFFF;
  text-transform: uppercase;
  font-weight: 500;
  font-size: 1.4rem;
  transition-property: background-color;
  transition-duration: .5s;
  transition-timing-function: ease;
}

.login__button:hover,
.login__button:focus {
  background-color: #EEEAF1;
  outline: none;
}

.login__button:active {
  position: relative;
  top: 2px;
  background-color: #63536C;
  color: #FFFFFF;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.75);
}

.conf-step__button+.conf-step__button,
.login__button+.conf-step__button,
.conf-step__button+.login__button,
.login__button+.login__button {
  margin-left: 12px;
}

.login__button {
  color: #FFFFFF;
  background-color: #16A6AF;
  padding: 12px 32px;
  margin-top: 17px;
}

.login__button:hover,
.login__button:focus {
  background-color: #2FC9D2;
  outline: none;
}

.login__button:active {
  position: relative;
  top: 2px;
  background-color: #146C72;
  color: #FFFFFF;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.75);
}


.login {
  width: 480px;
  margin: 0 auto;
  position: relative;
  background-color: rgba(234, 233, 235, 0.95);
}

.login__wrapper {
  padding: 35px 104px;
}

.login__header {
  position: relative;
  box-sizing: border-box;
  padding: 16px 104px;
  background-color: #63536C;
  color: #FFFFFF;
}

.login__title {
  font-size: 2.2rem;
  font-weight: 700;
  text-transform: uppercase;
}

.login__label {
  width: 100%;
  margin-bottom: 0.8rem;
}

.login__label:last-of-type {
  margin-bottom: 0;
}

.login__input {
  width: 100%;
}

.popup {
  display: none;
  position: fixed;
  width: 100%;
  height: 100%;
  z-index: 100;
}

.popup__title {
  font-size: 2.2rem;
  font-weight: 700;
  text-transform: uppercase;
}

.popup.active {
  display: block;
  background: rgba(0, 0, 0, 0.7);
  animation: 0.5s ease-out 0s 1 darken;
}

.popup.active .popup__content {
  top: 100px;
  animation: 0.5s ease-out 0s 1 slideFromTop;
}

.popup__container {
  position: relative;
  width: 100%;
}

.popup__content {
  position: relative;
  top: -50vh;
  background-color: rgba(234, 233, 235, 0.95);
  width: 960px;
  margin: 0 auto;
}

.popup__header {
  box-sizing: border-box;
  padding: 16px 42px;
  background-color: #63536C;
  color: #FFFFFF;
}

.popup__wrapper {
  padding: 35px 104px;
}

.popup__dismiss {
  float: right;
}

.popup__dismiss img {
  height: 1em;
  transition: .3s all ease;
}

.popup__dismiss img:hover {
  transform: scale(1.2);
  transition: .3s all ease;
}

/*==================================*/


.popup__poster {
  height: 200px;
  background-size: 100% 100%;
  background-repeat: no-repeat;
}

.popup__container {
  display: flex;
  justify-content: space-between;
}

.popup__form {
  width: 100%;
}


.trash-seance {
  width: 40px;
  height: 40px;
  position: absolute;
  left: -50px;
  top: 25px;
  display: none;
}

.explanation-text {
  font-size: 14px;
  color: #ff0000;
  font-weight: 600;
}


</style>