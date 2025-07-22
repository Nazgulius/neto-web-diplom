<script>
export default {
  name: 'Hall',

  data() {
    return { // тут состояние 
      seats: [], // данные о местах
      selectedSeats: [] // список выбранных мест
    }
  },
  methods: {
    // методы для бронирования 
    toggleSeat(seat) {
      // Проверяем, свободно ли место, меняем состояние
      if (seat.taken) return; // нельзя выбрать занятое
      seat.selected = !seat.selected;
      if (seat.selected) {
        this.selectedSeats.push(seat);
      } else {
        this.selectedSeats = this.selectedSeats.filter(s => s.id !== seat.id);
      }
    },

    reserveSeats() {
      // Отправка выбранных мест на сервер, например:
      axios.post('/api/reserve', {
        seats: this.selectedSeats.map(s => s.id)
      }).then(response => {
        alert('Бронирование успешно!');
      }).catch(error => {
        alert('Ошибка бронирования');
      });
    },

    fetchSeats() {
      // Загрузить состояние кресел с сервера (или заглушку)
      axios.get('/api/seats')
        .then(response => {
          this.seats = response.data;
        });
    }
  },
  mounted() {
    // fetch данных о зале 
    this.fetchSeats();
    document.body.classList.add('page-client');
  }
}


</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>

  <main>
    <section class="buying">
      <div class="buying__info">
        <div class="buying__info-description">
          <h2 class="buying__info-title">Звёздные войны XXIII: Атака клонированных клонов</h2>
          <p class="buying__info-start">Начало сеанса: 18:30</p>
          <p class="buying__info-hall">Зал 1</p>
        </div>
        <div class="buying__info-hint">
          <p>Тапните дважды,<br>чтобы увеличить</p>
        </div>
      </div>
      <div class="buying-scheme">
        <div class="buying-scheme__wrapper">
          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart" v-for="seat in seats" :key="seat.id"
              :class="['seat', { 'selected': seat.selected, 'taken': seat.taken }]" @click="toggleSeat(seat)">
              {{ seat.number }}></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart" v-for="seat in seats" :key="seat.id"
              :class="['seat', { 'selected': seat.selected, 'taken': seat.taken }]" @click="toggleSeat(seat)">
              {{ seat.number }}></span><span class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_taken"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_vip"></span>
            <span class="buying-scheme__chair buying-scheme__chair_vip"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_vip"></span><span
              class="buying-scheme__chair buying-scheme__chair_vip"></span>
            <span class="buying-scheme__chair buying-scheme__chair_vip"></span><span
              class="buying-scheme__chair buying-scheme__chair_vip"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_vip"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_taken"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_vip"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_taken"></span><span
              class="buying-scheme__chair buying-scheme__chair_vip"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_selected"></span>
            <span class="buying-scheme__chair buying-scheme__chair_selected"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
            <span class="buying-scheme__chair buying-scheme__chair_disabled"></span><span
              class="buying-scheme__chair buying-scheme__chair_disabled"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
          </div>

          <div class="buying-scheme__row">
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_taken"></span><span
              class="buying-scheme__chair buying-scheme__chair_taken"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
            <span class="buying-scheme__chair buying-scheme__chair_standart"></span><span
              class="buying-scheme__chair buying-scheme__chair_standart"></span>
          </div>
        </div>
        <div class="buying-scheme__legend">
          <div class="col">
            <p class="buying-scheme__legend-price"><span
                class="buying-scheme__chair buying-scheme__chair_standart"></span> Свободно (<span
                class="buying-scheme__legend-value">250</span>руб)</p>
            <p class="buying-scheme__legend-price"><span class="buying-scheme__chair buying-scheme__chair_vip"></span>
              Свободно VIP (<span class="buying-scheme__legend-value">350</span>руб)</p>
          </div>
          <div class="col">
            <p class="buying-scheme__legend-price"><span class="buying-scheme__chair buying-scheme__chair_taken"></span>
              Занято</p>
            <p class="buying-scheme__legend-price"><span
                class="buying-scheme__chair buying-scheme__chair_selected"></span> Выбрано</p>
          </div>
        </div>
      </div>
      <button class="acceptin-button" onclick="location.href='payment.html'" @click="reserveSeats">Забронировать</button>
    </section>
  </main>
</template>

<style>
@charset "UTF-8";

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

html {
  font-family: "Roboto", sans-serif;
  font-size: 62.5%;
}

body.page-client {
  background-image: url("../src/client/background.jpg");
  background-size: cover;
  background-attachment: fixed;
  background-position: right;
}

.page-header {
  padding: 1.4rem;
}

.page-header__title {
  margin: 0;
  font-weight: 900;
  font-size: 3.4rem;
  color: #FFFFFF;
  text-transform: uppercase;
}

.page-header__title span {
  font-weight: 100;
}

/* ~~~~~~~~~~~~ Кинозал ~~~~~~~~~~~~ */
.acceptin-button {
  display: block;
  margin: 3rem auto 0;
  box-shadow: 0px 3px 3px rgba(0, 0, 0, 0.24), 0px 0px 3px rgba(0, 0, 0, 0.12);
  border-radius: 3px;
  border: none;
  padding: 12px 57px;
  text-transform: uppercase;
  font-weight: 500;
  font-size: 1.4rem;
  background-color: #16A6AF;
  color: #FFFFFF;
}

.buying {
  background-color: rgba(241, 235, 230, 0.95);
  padding-bottom: 3rem;
}

.buying__info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.5rem;
}

.buying__info .buying__info-description {
  padding-right: 1.5rem;
}

.buying__info .buying__info-title,
.buying__info .buying__info-hall {
  font-size: 1.6rem;
  font-weight: 700;
}

.buying__info .buying__info-start {
  font-size: 1.4rem;
  font-weight: 300;
  margin: 0.7rem 0;
}

.buying__info .buying__info-hint {
  position: relative;
  font-weight: 300;
  font-size: 1.4rem;
  text-align: center;
}

.buying__info .buying__info-hint p {
  position: relative;
  width: 10rem;
}

.buying__info .buying__info-hint p::before {
  content: '';
  position: absolute;
  left: -1rem;
  top: calc(50% - 1.6rem);
  display: block;
  width: 2.4rem;
  height: 3.2rem;
  background-image: url(../src/client/hint.png);
  background-size: 2.4rem 3.2rem;
}

.buying-scheme {
  text-align: center;
  background-color: #171D24;
  padding: 1.5rem 3rem 1.5rem;
}

.buying-scheme__wrapper {
  font-size: 0;
  display: inline-block;
  background-image: url(../src/client/screen.png);
  background-position: top;
  background-repeat: no-repeat;
  background-size: 100%;
  padding-top: 3rem;
}

.buying-scheme__wrapper::before {
  content: '';
}

.buying-scheme__row+.buying-scheme__row {
  margin-top: 4px;
}

.buying-scheme__chair {
  display: inline-block;
  vertical-align: middle;
  width: 2rem;
  height: 2rem;
  border: 1px solid #525252;
  box-sizing: border-box;
  border-radius: 4px;
}

.buying-scheme__chair:not(:first-of-type) {
  margin-left: 4px;
}

.buying-scheme__chair_disabled {
  opacity: 0;
}

.buying-scheme__chair_standart {
  background-color: #FFFFFF;
}

.buying-scheme__chair_taken {
  background-color: transparent;
}

.buying-scheme__chair_vip {
  background-color: #F9953A;
}

.buying-scheme__chair_selected {
  background-color: #25C4CE;
  box-shadow: 0px 0px 4px #16A6AF;
  transform: scale(1.2);
}

.buying-scheme__legend {
  padding-top: 3rem;
  font-size: 1.4rem;
  font-weight: 300;
  color: #FFFFFF;
  display: flex;
  flex-wrap: nowrap;
  text-align: left;
}

.buying-scheme__legend .col {
  max-width: 25rem;
}

.buying-scheme__legend .col:first-of-type {
  padding-right: 1.5rem;
  margin-left: auto;
}

.buying-scheme__legend .col:last-of-type {
  margin-right: auto;
}

.buying-scheme__legend .buying-scheme__legend-price+.buying-scheme__legend-price {
  margin-top: 1rem;
}



@media screen and (min-width: 479px) {
  .page-nav .page-nav__day {
    padding: 1rem 2rem;
  }
}

@media screen and (min-width: 990px) {

  .page-header,
  nav,
  main {
    width: 990px;
    margin: auto;
  }

  .movie,
  .buying {
    border-radius: 2px;
  }

  .buying__info-hint {
    display: none;
  }
}
</style>