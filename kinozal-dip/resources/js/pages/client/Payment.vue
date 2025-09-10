<script>
import axios from 'axios';

export default {
  name: 'Payment',
  

  data() {
    return { // тут состояние 
      seats: [], 
      seatsForPayment: [], // данные о местах
      movieForPayment: [], // данные о зале и кино
      totalPrice: 0,
    }
  },
  methods: {    
    // Загрузка состояния кресел
    blockedSeats() {
      axios.get('http://127.0.0.1:8000/seats')
        .then(response => {
          this.seats = response.data.filter((el) => el.status ===  "blocked" );
          console.log('забронированные места:', this.seats);
        });
    },
    sumTotalPrice() {
      for (const seat of this.seatsForPayment) {        
        if (seat.type === 'standart') {
          this.totalPrice += this.movieForPayment.amountStandart;
        };
        if (seat.type === 'vip') {
          this.totalPrice += this.movieForPayment.amountVip;
        };
      }
      
      return this.totalPrice;
    },
    btnBookedSeats() {
      this.bookedSeats();

      this.$router.push({
        name: 'ticket',
          query: { 
            payload: JSON.stringify({
              seats: this.seatsForPayment, 
              movie: this.movieForPayment,
            })
          }        
      });
    },
    bookedSeats() {
      axios.post('http://127.0.0.1:8000/reserve-seats', {
        seats: this.seatsForPayment.map(s => s.id)
      }).then((response ) => {
        console.log('bookedSeats response ', response);
        if (response.status === 200 || response.status === 201) {
          // Сброс статуса выделения
          this.seatsForPayment.forEach(s => { s.selected = false; });
          this.seatsForPayment = [];
          alert('Бронирование успешно!');          
        } else {
          alert('Ошибка при обработке ответа сервера');
        }
      }).catch((error) => {
        console.error('Ошибка бронирования:', error);
        alert('Ошибка бронирования в bookedSeats');
      });
    },
    btnCenselBlockSeats() {
      this.censelBlockSeats();

      this.$router.push({
        name: 'Index'                
      });
    },
    censelBlockSeats() {
      axios.post('http://127.0.0.1:8000/censel-block-seats', {
        seats: this.seatsForPayment.map(s => s.id)
      }).then(() => {
        // сбросить статус выделения
        this.seatsForPayment.forEach(s => { s.selected = false; });
        this.seatsForPayment = [];
        alert('Отмена блокирования успешно!');
      }).catch(() => {
        alert('Ошибка бронирования в censelBlockSeats');
      });
    },

  },
  mounted() {
    document.body.classList.add('page-client');
    const payloadRaw = this.$route.query.payload;
    const payload = payloadRaw ? JSON.parse(payloadRaw) : null;
    
    this.seatsForPayment = payload?.seats ?? {};
    console.log('this.seatsForPayment ', this.seatsForPayment);
    this.movieForPayment = payload?.movie ?? {};
    
    this.sumTotalPrice(); 
     
    // this.blockedSeats();

    // это про принятие параметров по ссылке перехода (<router-link ...)
    // const seatsParam = this.$route.query.seats;
    // if (seatsParam) {
    //   try {
    //     this.seats = JSON.parse(seatsParam);
    //   } catch(e) {
    //     console.error('Ошибка парсинга seats', e);
    //   }
    // }

    // данных о зале 
    // async function confirmBooking() {
    //   const response = await axios.post('http://127.0.0.1:8000/api/book', {
    //     seat_id: seatId,
    //     session_id: sessionId,
    //     client_id: userId, // клиент из авторизации
    //   });
    //   // сохраняем полученный билет или uuid
    //   // и переходим на Ticket.vue, передавая его через маршруты или глобальное состояние
    //   this.$router.push({ name: 'ticket', params: { ticketUuid: response.data.ticket.uuid } });
    // }
    
  }
}


</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>
  
  <router-link :to="{ name: 'Index' }" class="link_color">
  Home
  </router-link>
  <main>
    <section class="ticket">
      
      <header class="tichet__check">
        <h2 class="ticket__check-title">Вы выбрали билеты:</h2>
      </header>
      
      <div class="ticket__info-wrapper">
        <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">{{ movieForPayment?.title }}</span></p>
        <p class="ticket__info">Места: <span v-for="seat in seatsForPayment" :key="seat" class="ticket__details ticket__chairs">{{seat?.id}}, </span></p>
        <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">{{ movieForPayment?.hall }}</span></p>
        <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">{{ movieForPayment?.time }}</span></p>
        <p class="ticket__info">Стоимость: <span class="ticket__details ticket__cost">{{ totalPrice }}</span> рублей</p>

      <button 
        class="acceptin-button"
        @click="btnBookedSeats"
      >
        Получить код бронирования
      </button>
      <button 
        class="acceptin-button"
        @click="btnCenselBlockSeats"
      >
        Отмена бронирования
      </button>

        <p class="ticket__hint">После оплаты билет будет доступен в этом окне, а также придёт вам на почту. Покажите QR-код нашему контроллёру у входа в зал.</p>
        <p class="ticket__hint">Приятного просмотра!</p>
      </div>
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
  background-blend-mode: multiply;  
  counter-reset: num;
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

/* ~~~~~~~~~~~~ Билет ~~~~~~~~~~~~ */
.tichet__check,
.ticket__info-wrapper {
  padding-left: 1.5rem;
  padding-right: 1.5rem;
  background-color: rgba(241, 235, 230, 0.95);
}

.tichet__check {
  position: relative;
  padding-top: 2.5rem;
  padding-bottom: 2.5rem;
}

.tichet__check::before {
  content: '';
  display: block;
  position: absolute;
  top: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-top.png);
  background-size: 10px 3px;
}

.tichet__check::after {
  content: '';
  display: block;
  position: absolute;
  bottom: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-bottom.png);
  background-size: 10px 3px;
}

.ticket__info-wrapper {
  position: relative;
  margin-top: 6px;
  padding-top: 2rem;
  padding-bottom: 3rem;
}

.ticket__info-wrapper::before {
  content: '';
  display: block;
  position: absolute;
  top: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-top.png);
  background-size: 10px 3px;
}

.ticket__info-wrapper::after {
  content: '';
  display: block;
  position: absolute;
  bottom: -3px;
  left: 0;
  right: 0;
  height: 3px;
  background-image: url(/src/client/border-bottom.png);
  background-size: 10px 3px;
}

.ticket__check-title {
  font-weight: 700;
  font-size: 2.2rem;
  text-transform: uppercase;
  color: #C76F00;
}

.ticket__info {
  font-size: 1.6rem;
  color: #000000;
}

.ticket__info+.ticket__info {
  margin-top: 1rem;
}

.ticket__details {
  font-weight: 700;
}

.ticket__hint {
  font-weight: 300;
  font-size: 1.4rem;
  margin-top: 3rem;
}

.ticket__hint+.ticket__hint {
  margin-top: 1rem;
}

.ticket__info-qr {
  display: block;
  margin: 3rem auto 0;
  width: 20rem;
  height: 20rem;
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
}</style>