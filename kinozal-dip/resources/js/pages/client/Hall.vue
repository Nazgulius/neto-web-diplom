<script>
import axios from 'axios';
import { useRouter } from 'vue-router';

export default {
  name: 'Hall',
  props: {
    hallId: [Number, String],
    seanceId: String,
    sessionId: {
      type: String,
      required: false,
      default() { return null; }
    }
  },
  data() {
    return { 
      seats: [], // данные о местах
      selectedSeats: [], // список выбранных мест
      session: null,
      movie: [],
      // hall: null,
      hall: [], // было
      
      // sessionId: this.sessionId // динамическая генерация сессии
    }
  },
  created() {
    const sessionId = this.$route.params.sessionId;
    
    // this.loadSession(sessionId);
  },
  computed: {
    // rows() {
    //   // Получим уникальные номера рядов        
    //   const uniqueRows = new Set(this.seats.map(seat => seat.row));
    //   return Array.from(uniqueRows).sort((a, b) => a - b);
    // },
  },
  methods: {    
    // Возвращает сидения в заданном ряду для текущего активного зала
    seatsInRow(row) {
      const currentHallId = this.hall?.id ?? this.hallId; // если hall загружен в этом.hall
      return this.seats.filter(s => s.row === row && s.hall_id === currentHallId);
    },

    // Переключает выбор места
    toggleSeat(seat) {
      // Разрешаем только для доступных мест (не занятые и не заблокированные)
      if (seat.status === 'blocked' || seat.status === 'booked') return;
      seat.selected = !seat.selected; // Переключение состояния
      this.updateSelectedSeats(seat); // Обновляем список выбранных мест
    },

    // Обновляет список выбранных мест
    updateSelectedSeats(seat) {
      if (seat.selected) {
        if (!this.selectedSeats.find(s => s.id === seat.id)) {
          this.selectedSeats.push(seat);
        }
      } else {
        this.selectedSeats = this.selectedSeats.filter(s => s.id !== seat.id);
      }
    },    

    // Группировка сидений по рядам (можно оставить как есть)
    seatsByRow(row) {
      return this.seats.filter(seat => seat.row === row);
    },
    
    // Проверка, выбран ли Seat
    isSelected(seat) {
      return this.selectedSeats.some(s => s.id === seat.id);
    },
    getSeatClass(seat) {      
      const classes = [
        'buying-scheme__chair',
        seat.type === 'Обычное' ? 'buying-scheme__chair_standart' : '',
        seat.type === 'VIP' ? 'buying-scheme__chair_vip' : '',
        seat.status === 'blocked' ? 'blocked' : '',
        seat.status === 'booked' ? 'occupied' : '',
        this.isSelected(seat) ? 'selected' : '',
        seat.taken === true ? 'buying-scheme__chair_selected' : ''
      ];

      // удаляем пустые строки и объединяем в строку классов      
      return classes.filter(Boolean).join(' ');
    },
    // Обработка выбора места с проверками
    async selectSeat(row, seat) {
      console.log('selectSeat ', row, seat);
      if (seat.status === 'booked' || seat.status === 'blocked') {  
        alert('Место занято');  
        return;  
      }  
      // можно вызывать тоггл 
      this.toggleSeat(seat);

      // попробуем скрыть оставшийся код метода

        // if (!this.isSeatAvailable(seat)) {
        //   alert('Место недоступно');
        //   return;
        // }

      if (seat.status === 'booked') {  
        alert('Место занято');  
        return;  
      }  

      // if (!this.sessionId) {
      //   alert('Нет sessionId');
      //   return;
      // }

      // try {
      //   const response = await axios.post('http://127.0.0.1:8000/check-seat', {
      //     seat_id: seat.id,
      //     session_id: this.sessionId,
      //   });
      //   if (response.data.available) {
      //     seat.status = 'blocked';
      //     // можно добавить в выбранные  
      //     if (!this.isSelected(seat)) {  
      //       this.selectedSeats.push(seat);  
      //     }  
      //   } else {
      //     alert('Место уже занято или заблокировано другим пользователем');
      //   }
      // } catch (err) {
      //   console.error('Ошибка сервера:', err.response?.data || err.message);
      // }
    },
    // Отправка выбранных мест на сервер
    reserveSeats() {
      axios.post('http://127.0.0.1:8000/seats/reserve', {
        seats: this.selectedSeats.map(s => s.id)
      }).then(() => {
        // сбросить статус выделения
        this.selectedSeats.forEach(s => { s.selected = false; });
        this.selectedSeats = [];
        alert('Бронирование успешно!');
        this.getSeats();
      }).catch(() => {
        alert('Ошибка бронирования');
      });
    },

    // Проверка доступности места
    isSeatAvailable(seat) {
      console.log('isSeatAvailable ', seat);
      console.log('isSeatAvailable ', seat.status);
      return seat.status === 'available' || seat.status === 'blocked';
    },
    getSeats() {
      axios.get('http://127.0.0.1:8000/seats/index')
        .then(response => {
          this.seats = response.data;
          console.log('getSeats response: ', response);
          console.log('getSeats - seats: ', this.seats);
        })
        .catch(error => {
          console.error(error);
        });
    },
    async loadSession(sessionId) {
      console.log('loadSession sessionId', sessionId);
      if (!sessionId) {
        console.error('loadSession: no sessionId');
        return;
      }
      try {
        const res = await axios.get(`http://127.0.0.1:8000/hall/sessions/${sessionId}`);
        this.session = res.data;
        this.loadHall(res.data.hall_id);
        this.loadMovie(res.data.movie_id);
      } catch (e) {
        console.error('Не удалось загрузить данные сессии', e);
      }
    },
    async loadHall(hallId) {
      try {
        const res = await axios.get(`http://127.0.0.1:8000/hall/hall/${hallId}`);
        this.hall = res.data;
      } catch (e) {
        console.error('Не удалось загрузить данные сессии', e);
      }
    },
    async loadMovie(movieId) {
      try {
        const res = await axios.get(`http://127.0.0.1:8000/hall/movie/${movieId}`);
        this.movie = res.data;
      } catch (e) {
        console.error('Не удалось загрузить данные сессии', e);
      }
    },
    
  },

  mounted() {
    document.body.classList.add('page-client');
    
    console.log('sessionId=', this.sessionId);
    console.log('Hall ID:', this.hallId);

    const route = this.$route;
    console.log('route params:', route.params);
    const sess = route.params.sessionId || this.sessionId;
    const hall = route.params.hallId || this.hallId;

    if (sess) {
      this.loadSession(sess);
    } else if (hall) {
      this.loadHall(hall);
    }

    // Загружаем данные по месту и залу при монтировании компонента
    // if (this.sessionId) {
    //   this.loadSession(this.sessionId);
    // } else if (this.hallId) {
    //   // если сессия не передана, можно просто загрузить зал и его seats
    //   this.loadHall(this.hallId);
    //   this.getSeats();
    // }

    this.getSeats();    
  },
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
          <h2 class="buying__info-title">{{ movie?.title }} </h2>
          <p class="buying__info-start">Начало сеанса: {{ session?.start_time }}</p>
          <p class="buying__info-hall">Зал {{ hall?.name }}</p>
        </div>
        <div class="buying__info-hint">
          <p>Тапните дважды,<br>чтобы увеличить</p>
        </div>
      </div>
      <div class="buying-scheme">
        <div class="buying-scheme__wrapper">

          <div class="hall-room">
            <div v-for="row in hall.rows" :key="row" class="row">
              <!-- <div v-for="seat in hall.seats_per_row" :key="seat.id"  -->
              <div v-for="seat in seatsInRow(row)" :key="seat.id" 
              :class="getSeatClass(seat)"
              @click="selectSeat(row, seat)">
                {{ seat.seat_number }}
              </div>
            </div>
          </div>
                <!-- <span class="buying-scheme__chair buying-scheme__chair_standart"></span> -->

                <!-- <router-link class="acceptin-button"
                  :to="{ name: 'payment', params: { seatId: seat.id, sessionId: session.id } }">
                  Забронировать
                </router-link> -->

            
            <!-- <div v-for="seat in seats" :key="seat.id" class="seat"
              :class="{ 'blocked': seat.status === 'blocked', 'occupied': seat.status === 'booked' }"
              @click="selectSeat(seat)" :disabled="seat.status !== 'available'">
              {{ seat.number }}, -->

              <!-- <router-link class="acceptin-button"
                :to="{ name: 'Payment', params: { seatId: seat.id, sessionId: this.sessionId } }">
                Забронировать
              </router-link> -->
            <!-- </div> -->




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
      <button class="acceptin-button"><a :href="route('payment')">Забронировать</a></button>
      <router-link :to="{ name: 'payment' }" class="acceptin-button">Забронировать</router-link>
      <button class="acceptin-button"><router-link :to="{ name: 'payment', query: { 
        payload: JSON.stringify({
          seats: selectedSeats,
          movie: {
            title: movie?.title || '',
            hall: hall?.name || '',
            time: session?.start_time || '',
            amountStandart: hall?.amountStandart,
            amountVip: hall?.amountVip,
          },
          seanceId: seanceId
        })}}" 
        >
        Забронировать 
      </router-link></button>
      <!-- <button class="acceptin-button" @click="$router.push('/payment')">Забронировать</button> -->
      <!-- <button class="acceptin-button"><a :href="{ name: 'payment', params: { seatId: seat.id, sessionId: session.id } }">Забронировать</a></button> -->
      <!-- <router-link :to="{ name: 'payment', query: { seats: JSON.stringify(seats) } }" class="acceptin-button">Забронировать</router-link> -->


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
  background-image: url("/src/client/background.jpg");
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
  background-image: url(/src/client/hint.png);
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
  background-image: url(/src/client/screen.png);
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

/* .seat {
  width: 20px;
  height: 20px;
  display: inline-block;
  margin: 3px;
  background-color: #eee;
  cursor: pointer;
} */
.buying-scheme__chair, .seat {
  display: inline-block;
  vertical-align: middle;
  width: 2rem;
  height: 2rem;
  border: 1px solid #525252;
  box-sizing: border-box;
  border-radius: 4px;
  background-color: #FFFFFF;
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

.seat.blocked {
  background-color: orange;
}

.seat.occupied {
  background-color: rgb(0, 0, 0);
  /* background-color: #ccc; */
  cursor: not-allowed;
}

.seat.selected {
  background-color: #2c8;
}

.buying-scheme__chair { background: #fff; border-color: #525252; }
.buying-scheme__chair_standart { background: #fff; }
.buying-scheme__chair_vip { background: #F9953A; }
.buying-scheme__chair_selected { background: #25C4CE; box-shadow: 0 0 4px #16A6AF; transform: scale(1.2); }
.blocked { background: orange ; }          /*!important осторожно: !important временно для теста */
.occupied { background: rgb(0,0,0) ; cursor: not-allowed; } /*!important */
.selected { background-color: #2c8; }               /* ваш стиль для выбранного, если применимо */

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

.hall-room {
  /* display: inline-grid; */

  /* количество колонок */
  grid-template-columns: repeat(10, 1fr);
  gap: 5px;
  /* промежутки между креслами */
  padding: 5px;
}

/* .seat {
  width: 2rem;
  height: 2rem;
  line-height: 20px;
  text-align: center;
  border-radius: 4px;
  cursor: pointer;
  background-color: #eee;
} */



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