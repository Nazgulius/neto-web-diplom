<script>
import axios from 'axios';

export default {
  name: 'Login',

  data() {
    return { // тут состояние 
    }  }, 
      methods: {
        // методы для бронирования 
      },
      mounted() {
        // fetch данных о зале 
        document.body.classList.add('page-admin');
        axios.get('/movies')
          .then(response => {
            console.log(response.data);
          })
          .catch(error => {
            console.error(error);
          });
      }
    }

    // из файла js/accordeon.js
    const headers = Array.from(document.querySelectorAll('.conf-step__header'));
    headers.forEach(header => header.addEventListener('click', () => {
      header.classList.toggle('conf-step__header_closed');
      header.classList.toggle('conf-step__header_opened');
    }));
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
button,
.conf-step__header,
select {
  cursor: pointer;
}

.text-center {
  text-align: center;
}

.conf-steps,
.page-header {
  width: 972px;
  margin: 0 auto;
}

.conf-step__wrapper,
.conf-step__header,
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

.conf-step {
  position: relative;
  background-color: rgba(234, 233, 235, 0.95);
}

.conf-step::before {
  content: '';
  position: absolute;
  left: 62px;
  margin-left: -1px;
  top: 0;
  bottom: 0;
  display: block;
  width: 2px;
  background-color: #BC95D6;
}

.conf-step__header_opened+.conf-step__wrapper {
  display: block;
}

.conf-step__header_closed+.conf-step__wrapper {
  display: none;
}

.conf-step__header_closed::after {
  transform: rotate(-90deg);
}

.conf-step__header {
  position: relative;
  box-sizing: border-box;
  background-color: #63536C;
  color: #FFFFFF;
  transition-property: all;
  transition-duration: .6s;
  transition-timing-function: ease;
}

.conf-step__header::before {
  content: '';
  position: absolute;
  left: 62px;
  margin-left: -1px;
  top: 0;
  bottom: 0;
  display: block;
  width: 2px;
  background-color: #BC95D6;
}

.conf-step__header::after {
  content: '';
  position: absolute;
  top: calc(50% - 8px);
  display: block;
  right: 42px;
  width: 24px;
  height: 16px;
  background-image: url("../src/admin/switch.png");
  background-size: 24px 16px;
}

.conf-step__header:hover,
.conf-step__header:focus {
  background-color: #89639e;
}

.conf-step__header .conf-step__title {
  font-size: 2.2rem;
  font-weight: 700;
  text-transform: uppercase;
  counter-increment: num;
  transition-property: all;
  transition-duration: .6s;
  transition-timing-function: ease;
}

.conf-step__header .conf-step__title::before {
  content: counter(num);
  position: absolute;
  display: block;
  left: 62px;
  top: 50%;
  margin-left: -22px;
  margin-top: -22px;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  border: 5px solid #BC95D6;
  background-color: #FFFFFF;
  box-sizing: border-box;
  color: #63536C;
  font-weight: 900;
  font-size: 2.8rem;
  text-align: center;
  letter-spacing: normal;
}

.conf-step__header:hover .conf-step__title {
  letter-spacing: 2px;
  transition-property: all;
  transition-duration: .6s;
  transition-timing-function: ease;
}

.conf-step__header:hover .conf-step__title::before {
  letter-spacing: normal;
}

.conf-step:first-of-type .conf-step__header::before {
  top: 50%;
}

.conf-step:last-of-type .conf-step__header::before {
  bottom: 50%;
}

.conf-step:last-of-type::before {
  display: none;
}

.conf-step__paragraph {
  font-size: 1.6rem;
  font-weight: 400;
  color: #000000;
  margin-bottom: 12px;
}

.conf-step__paragraph:not(:first-of-type) {
  margin-top: 35px;
}

.conf-step__list {
  list-style: none;
  font-size: 1.6rem;
  text-transform: uppercase;
  font-weight: 500;
  margin: 14px 0 5px 25px;
}

.conf-step__list li {
  position: relative;
}

.conf-step__list li+li {
  margin-top: 10px;
}

.conf-step__list li::before {
  content: '–';
  position: absolute;
  left: -14px;
}

.conf-step__button,
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

.conf-step__button:hover,
.conf-step__button:focus,
.login__button:hover,
.login__button:focus {
  background-color: #EEEAF1;
  outline: none;
}

.conf-step__button:active,
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

.conf-step__buttons {
  margin-top: 10px;
}

.conf-step__button-regular {
  color: #63536C;
  padding: 12px 32px;
  margin-top: 17px;
}

.conf-step__button.conf-step__button-accent,
.login__button {
  color: #FFFFFF;
  background-color: #16A6AF;
  padding: 12px 32px;
  margin-top: 17px;
}

.conf-step__button.conf-step__button-accent:hover,
.conf-step__button.conf-step__button-accent:focus,
.login__button:hover,
.login__button:focus {
  background-color: #2FC9D2;
  outline: none;
}

.conf-step__button.conf-step__button-accent:active,
.login__button:active {
  position: relative;
  top: 2px;
  background-color: #146C72;
  color: #FFFFFF;
  box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.75);
}

.conf-step__button-trash {
  position: relative;
  width: 20px;
  height: 20px;
  vertical-align: text-top;
}

.conf-step__button-trash::before {
  content: '';
  position: absolute;
  top: 4px;
  left: 4px;
  width: 12px;
  height: 12px;
  background-image: url("../src/admin/trash-sprite.png");
  background-position: 0 0;
  background-size: 24px 12px;
  background-repeat: no-repeat;
}

.conf-step__button-trash:active::before {
  background-position: -12px 0;
}

.conf-step__legend {
  color: #848484;
  font-size: 1.4rem;
  margin-bottom: 12px;
}

.conf-step__legend .multiplier {
  font-family: monospace;
  font-size: 18px;
  padding: 0 10px;
}

.conf-step__legend .conf-step__chair:not(:first-of-type) {
  margin-left: 20px;
}

.conf-step__hint {
  margin-top: 10px;
}

.conf-step__label,
.login__label {
  display: inline-block;
  font-size: 1.2rem;
  color: #848484;
}

.conf-step__label-fullsize {
  width: 100%;
  margin-bottom: 0.8rem;
}

.conf-step__label-fullsize :last-of-type {
  margin-bottom: 0;
}

.conf-step__label-fullsize .conf-step__input {
  width: 100%;
}

.conf-step__input,
.login__input {
  display: block;
  width: 100px;
  margin-top: 1px;
  padding: 8px;
  font-size: 1.6rem;
  color: #000000;
  border: 1px solid #b7b7b7;
}

.conf-step__input:focus,
.login__input:focus {
  outline: 1px #CF87FF solid;
  outline-offset: -1px;
}

.conf-step__chair {
  display: inline-block;
  vertical-align: middle;
  width: 26px;
  height: 26px;
  border-width: 2px;
  border-style: solid;
  box-sizing: border-box;
  border-radius: 4px;
}

.conf-step__chair_standart {
  border-color: #393939;
  background-color: #C4C4C4;
}

.conf-step__chair_vip {
  border-color: #0a828a;
  background-color: #b0d6d8;
  background-image: url("../src/admin/green-pattern.png");
  background-repeat: repeat;
}

.conf-step__chair_disabled {
  border-color: #C4C4C4;
}

.conf-step__selectors-box {
  font-size: 0;
  list-style: none;
  margin-bottom: 15px;
}

.conf-step__selectors-box li {
  position: relative;
  display: inline-block;
  font-size: 1.2rem;
}

.conf-step__selectors-box .conf-step__radio {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 20;
  width: 80px;
  height: 42px;
  opacity: 0;
}

.conf-step__selectors-box .conf-step__selector {
  position: relative;
  display: block;
  padding: 13px 21px;
  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.24), 0px 0px 3px rgba(0, 0, 0, 0.12);
  border-radius: 3px;
  border: none;
  background-color: rgba(255, 255, 255, 0.45);
  text-transform: uppercase;
  font-weight: 500;
  font-size: 1.4rem;
  transition-property: background-color;
  transition-duration: .5s;
  transition-timing-function: ease;
  z-index: 5;
}

.conf-step__selectors-box .conf-step__radio:checked+.conf-step__selector {
  background-color: #FFFFFF;
  box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.24), 0px 2px 2px rgba(0, 0, 0, 0.24), 0px 0px 2px rgba(0, 0, 0, 0.12);
  transform: scale(1.1);
  font-weight: 900;
  font-size: 1.4rem;
  z-index: 10;
}

.conf-step__selectors-box .conf-step__radio:hover+.conf-step__selector {
  background-color: rgba(255, 255, 255, 0.9);
}

.conf-step__hall {
  position: relative;
  padding: 62px 32px 34px;
  border: 2px solid #000000;
  text-align: center;
}

.conf-step__hall::before {
  content: ' экран';
  position: absolute;
  top: 24px;
  left: 1.2em;
  right: 0;
  font-size: 1.6rem;
  letter-spacing: 1.2em;
  text-transform: uppercase;
}

.conf-step__hall .conf-step__chair {
  cursor: pointer;
}

.conf-step__hall .conf-step__hall-wrapper {
  display: inline-block;
}

.conf-step__hall .conf-step__hall-wrapper .conf-step__row {
  font-size: 0;
}

.conf-step__hall .conf-step__hall-wrapper .conf-step__row+.conf-step__row {
  margin-top: 10px;
}

.conf-step__hall .conf-step__hall-wrapper .conf-step__chair+.conf-step__chair {
  margin-left: 10px;
}

.conf-step__movies {
  display: flex;
  flex-wrap: wrap;
  align-items: flex-start;
}

.conf-step__movies .conf-step__movie {
  position: relative;
  width: calc((100% - 30px) / 3);
  min-height: 52px;
  padding: 8px 8px 8px 48px;
  background: #FFEB85;
  border: 1px solid rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  cursor: pointer;
}

.conf-step__movies .conf-step__movie:nth-of-type(3n + 2) {
  margin: 0 15px;
}

.conf-step__movies .conf-step__movie:nth-of-type(n + 4) {
  margin-top: 15px;
}

.conf-step__movies .conf-step__movie:nth-of-type(1) {
  background-color: #caff85;
}

.conf-step__movies .conf-step__movie:nth-of-type(2) {
  background-color: #85ff89;
}

.conf-step__movies .conf-step__movie:nth-of-type(3) {
  background-color: #85ffd3;
}

.conf-step__movies .conf-step__movie:nth-of-type(4) {
  background-color: #85e2ff;
}

.conf-step__movies .conf-step__movie:nth-of-type(5) {
  background-color: #8599ff;
}

.conf-step__movies .conf-step__movie:nth-of-type(6) {
  background-color: #ba85ff;
}

.conf-step__movies .conf-step__movie:nth-of-type(7) {
  background-color: #ff85fb;
}

.conf-step__movies .conf-step__movie:nth-of-type(8) {
  background-color: #ff85b1;
}

.conf-step__movies .conf-step__movie:nth-of-type(9) {
  background-color: #ffa285;
}

.conf-step__movies .conf-step__movie-poster {
  position: absolute;
  top: 0;
  left: 0;
  width: 38px;
  height: 50px;
}

.conf-step__movies .conf-step__movie-title {
  font-weight: 500;
  font-size: 1.4rem;
}

.conf-step__movies .conf-step__movie-duration {
  font-size: 1.4rem;
  color: rgba(0, 0, 0, 0.7);
}

.conf-step__seances-hall {
  position: relative;
  margin: 35px;
}

.conf-step__seances-title {
  font-weight: 500;
  font-size: 1.6rem;
  text-transform: uppercase;
}

.conf-step__seances-timeline {
  position: relative;
  outline: 1px solid #848484;
  padding: 10px 0;
  height: 40px;
  box-sizing: content-box;
  width: calc(1440px * 0.5);
}

.conf-step__seances-movie {
  position: absolute;
  height: 40px;
  border: 1px solid rgba(0, 0, 0, 0.3);
  box-sizing: border-box;
  padding: 10px 2px 10px 10px;
}

.conf-step__seances-movie .conf-step__seances-movie-title {
  overflow: hidden;
  line-height: 10px;
  height: 100%;
}

.conf-step__seances-movie .conf-step__seances-movie-start {
  position: absolute;
  bottom: -33px;
  left: -14px;
  color: #848484;
  font-size: 1.2rem;
}

.conf-step__seances-movie::before {
  content: '';
  position: absolute;
  display: block;
  width: 1px;
  height: 5px;
  bottom: -17px;
  left: 0;
  background-color: #848484;
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

.conf-step__wrapper__save-status {
  padding-top: 10px;
  font-size: 15px;
  font-weight: 700;
  color: #008000;
}

.conf-step__button:disabled {
  background-color: #888888;
}

textarea.conf-step__input {
  min-height: 80px;
  max-height: 80px;
  min-width: 100%;
  max-width: 100%;
}

.conf-step__movie>a {
  position: absolute;
  bottom: 5px;
  right: 5px;
}

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

/*# sourceMappingURL=styles.css.map */
</style>