<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';

export default {
  name: 'Ticket',
  
  setup() {
    const ticket = ref(null);
    const qrCodeUrl = ref('');

    // Получить uuid из маршрута
    const route = useRoute();  // получаем текущий маршрут
    const uuid = route.params.uuid; // предполагаем, что в маршруте /ticket/:uuid

    // onMounted(async () => {
    //   try {
    //     const response = await axios.get(`http://127.0.0.1:8000/tickets/${uuid}`);
    //     ticket.value = response.data.ticket;
    //     qrCodeUrl.value = response.data.qr_code_url;
    //   } catch (error) {
    //     console.error('Ошибка при загрузке билета:', error);
    //   }
    // });

    return { ticket, qrCodeUrl };
  },
  data() {
    return { // тут состояние 
      qrCodeData: '',
      seats: [],
      seatsForPayment: [], // данные о местах
      movieForPayment: [], // данные о зале и кино
      totalPrice: 0,
    }
  },
  computed: {
    editDataForQR() {
       // Если seatsForPayment ещё пустой или не определён — вернем пустую строку
      const seatsArray = Array.isArray(this.seatsForPayment) ? this.seatsForPayment : [];
      const seatsStr = seatsArray.map(s => s.id).join(', ');

      const hall = this.movieForPayment?.hall ?? '';
      const title = this.movieForPayment?.title ?? '';
      const time = this.movieForPayment?.time ?? '';
      const total = this.totalPrice ?? 0;

      return `Зал: ${hall}. Фильм: ${title}. Места: ${seatsStr}. Время начала: ${time}. Сумма: ${total} рублей.`;
    }
  },
  methods: {
    async  blockedSeats() {
      await axios.get('http://127.0.0.1:8000/seats')
        .then(response => {
          this.seats = response.data.filter((el) => el.status ===  "blocked" );
          console.log('забронированные места:', this.seats);
        });
    },
    // новый вариант генерации qr кода
    async generateQrWithSeats() {
      try {

        const payloadForServer = this.editDataForQR;
        // const payloadForServer = {
        //   text: this.editDataForQR, // читаемая строка
        //   // seats: this.seatsForPayment.map(s => s.id)
        // };        
        // console.log('payloadForServer перед отправкой:', payloadForServer);
        const seatsData = JSON.stringify(payloadForServer);

        const response = await axios.post('http://127.0.0.1:8000/get-qr-code', { seats: seatsData });
        this.qrCodeData = response.data.qr_code;
      } catch (error) {
        console.error('Ошибка при генерации QR:', error);
      }
    },
    
    sumTotalPrice() {
      for (const seat of this.seatsForPayment) {        
        if (seat.type === 'Обычное') {
          this.totalPrice += this.movieForPayment.amountStandart;
        };
        if (seat.type === 'VIP') {
          this.totalPrice += this.movieForPayment.amountVip;
        };
      }
      return this.totalPrice;
    },
  },
  mounted() {
    document.body.classList.add('page-client');
    document.body.classList.add('page-client-hall');

    const payloadRaw = this.$route.query.payload;
    const payload = payloadRaw ? JSON.parse(payloadRaw) : null;

    this.seatsForPayment = payload?.seats ?? {};
    // console.log('this.seatsForPayment ', this.seatsForPayment);
    this.movieForPayment = payload?.movie ?? {};

    this.sumTotalPrice();
    
    this.generateQrWithSeats();
    

    // this.blockedSeats(); // получаем список забранированных сидений
  },
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>

  <nav class="page-nav">  
    <router-link :to="{ name: 'Index' }" class="link_login">
      Home
    </router-link>
  </nav>

  <main>
    <section class="ticket">
      
      <header class="tichet__check">
        <h2 class="ticket__check-title">Электронный билет</h2>
      </header>
      
      <div class="ticket__info-wrapper">
        <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">{{ movieForPayment?.title }}</span></p>
        <p class="ticket__info">Места: <span v-for="seat in seatsForPayment" :key="seat" class="ticket__details ticket__chairs">{{seat?.id}}, </span></p>
        <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">{{ movieForPayment?.hall }}</span></p>
        <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">{{ movieForPayment?.time }}</span></p>

        <div v-if="qrCodeData">
          <h1>QR Code: </h1>
          <img :src="qrCodeData" alt="QR Code" />
        </div>
        <div v-else>
          <p>Загрузка билета...</p>
        </div> 

        <p class="ticket__hint">Покажите QR-код нашему контроллеру для подтверждения бронирования.</p>
        <p class="ticket__hint">Приятного просмотра!</p>
      </div>
    </section>     
  </main>

</template>

<style >
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

body.page-client-hall{
  height: 100vh;
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