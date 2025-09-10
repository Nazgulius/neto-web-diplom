<script>
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';

export default {
  name: 'Index',
  props: {
    hallId: {
      type: Number,
      required: false,
      default() { return 1; }
    },
    sessionId: {
      type: String,
      required: false,
      default() { return 'abc123'; }
    },
    date: {
      type: String,
      required: false,
      default: () => {
          return new Date().toISOString().split('T')[0];
      }
    },
  },
  components: {
    Head,
    Link,
  },
  created() {
    this.fetchStatus();
  },
  data() {
    return {       
      movies: [],
      sessions: [],
      halls: [],
      globalSalesOpen: true,
      qrCodeData: 'тест qr кода',
      pollInterval: null,
      isFetching: false,
      selectedDate: null,
      daysOfWeek: [],
      filteredMovies: [],
    }
  },  
  methods: {
    // получение всех фильмов
    async getMovies() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/movies');
        this.movies = response.data;
        // await this.filterMoviesByDate(this.formatDate(this.selectedDate));
      } catch (error) {
        console.error('Ошибка при получении фильмов:', error);
      }
    },
    // получение всех сеансов
    getSessions() {
      axios.get('http://127.0.0.1:8000/sessions')
        .then(response => {
          this.sessions = response.data;
          // console.log('sessions: ', this.sessions);
        });
    },
    // получение всех Hall
    getHalls() {
    axios.get('http://127.0.0.1:8000/hall/index')
      .then(response => {
        this.halls = response.data;
        // console.log('halls: ', this.halls);
      })
      .catch(error => {
        console.error(error);
      });
    },
    // периодическое обновление залов, кино, сессий
    startPolling() {
      // Запускаем интервал опроса
      this.pollInterval = setInterval(() => {
        this.fetchHalls();
      }, 5000); // Каждые 5 секунд
    },
    stopPolling() {
      // Очищаем интервал
      clearInterval(this.pollInterval);
    },
    async fetchHalls() {
      try {
        // Проверяем, не выполняется ли уже запрос
        if (this.isFetching) return;
        if (!this.isValidDate(this.date)) {
          this.date = this.getDefaultDate();
        }
        
        this.isFetching = true;
        
         // Обновляем данные
        this.getMovies();
        this.getSessions();
        this.getHalls();
        console.log("fetchHalls Успешное обновление!");
               
      } catch (error) {
        console.error('Ошибка при получении данных:', error);
      } finally {
        this.isFetching = false;
      }
    },

    proceedToBooking() {
      this.checkGlobalOpen();
      const session = this.sessions.find(s => s.id === this.selectedSessionId);
      if (!session) {
        this.$toast.error('Сеанс не найден. Выберите другой сеанс.');
        return;
      }
      if (!session.is_open) {
        this.$toast.warn('Продажа билетов для этого сеанса сейчас закрыта.');
        return;
      }

      // Продолжаем к экрану Hall.vue 
      this.$router.push({ name: 'Hall', params: { sessionId: session.id } });
    },    
    updateSessionInList(updatedSession) {
      if (!updatedSession) return;

      const idx = this.sessions.findIndex(s => s.id === updatedSession.id);
      if (idx !== -1) {
        this.$set(this.sessions, idx, updatedSession); // Vue 2
        // В Vue 3 можно напрямую: this.sessions[idx] = updatedSession;
      } else {
        this.sessions.push(updatedSession);
      }
    },
    // проверка глобального статуса
    async fetchStatus() {
      try {
        const res = await axios.get('http://127.0.0.1:8000/admin/sales/status');
        this.globalSalesOpen = !!res.data.data.sales_globally_open;
      } catch (e) {
        console.error('Не удалось получить статус глобальных продаж', e);
      }
    },
    // генерация календаря на неделю
    generateDaysOfWeek() {
      try {
        const startDate = new Date();
        const today = new Date();
        const days = [];
        
        for (let i = 0; i < 7; i++) {
          const date = new Date(startDate);
          date.setDate(date.getDate() + i);
          days.push({
            date: this.formatDate(date),
            weekday: this.getWeekday(date),
            day: date.getDate(),
            month: date.getMonth() + 1,
            year: date.getFullYear(),
            isToday: this.isSameDay(date, today) // может и не нужна
          });
        }
        console.log('Текущая дата:', this.date, typeof this.date);
        
        this.daysOfWeek = days;
      } catch (error) {
        console.error('Ошибка при генерации дней недели:', error);
      }
    },
    isSameDay(date1, date2) {
      return (
        date1.getFullYear() === date2.getFullYear() &&
        date1.getMonth() === date2.getMonth() &&
        date1.getDate() === date2.getDate()
      );
    },
    formatDate(date) {
      if (!date) return '';
      if (!(date instanceof Date)) {
        date = new Date(date);
      }
      if (isNaN(date)) {
        return new Date().toISOString().split('T')[0];
      }

      return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    },
    getWeekday(date) {
      const weekdays = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'];
      return weekdays[date.getDay()];
    },
    isWeekend(date) {
      const day = new Date(date);
      return day.getDay() === 0 || day.getDay() === 6;
    },
    isToday(date) {
      const today = new Date();
      const testDate = new Date(date);
      return (
        testDate.getDate() === today.getDate() &&
        testDate.getMonth() === today.getMonth() &&
        testDate.getFullYear() === today.getFullYear()
      );
    },
    handleDateClick(date) {
      try {
        this.selectedDate = date;
        this.filterMoviesByDate(date);
      } catch (error) {
        console.error('Ошибка при выборе даты:', error);
      }
    },
    async filterMoviesByDate(date) {
      try {
        // Преобразуем дату в начало и конец дня
        const startOfDay = new Date(date);
        const endOfDay = new Date(date);
        endOfDay.setHours(23, 59, 59);

        // Собираем movie_id всех сеансов, которые попадают в выбранный день
        const movieIdsForDate = new Set(
          this.sessions.filter(session => {
            const sessionDate = new Date(session.start_datetime);
            return (
              sessionDate >= startOfDay &&
              sessionDate <= endOfDay
            );
          }).map(session => session.movie_id)
        );

        // Фильтруем фильмы по собранным ID
        this.filteredMovies = this.movies.filter(movie => 
          movieIdsForDate.has(movie.id)
        );
      } catch (error) {
        console.error('Ошибка при фильтрации фильмов:', error);
      }
    },    
    getCurrentDate() {
      let a = this.formatDate(new Date());
      console.log('getCurrentDate a Текущая дата:', a, typeof a);
      return a;
    },
    fetchMovies() {
      this.getMovies();
    },
    // приводим дату к нужному формату времени
    formatTime(datetime) {
      const date = new Date(datetime);
      
      // Получаем часы и минуты
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      
      return `${hours}:${minutes}`;
    },
    isValidDate(dateString) {
      return /^\d{4}-\d{2}-\d{2}$/.test(dateString) && !isNaN(new Date(dateString));
    },
    getDefaultDate() {
      return new Date().toISOString().split('T')[0];
    },
    async fetchData() {
      try {
        await this.getMovies();
        await this.getSessions();
        await this.getHalls();
        await this.filterMoviesByDate(this.selectedDate);
      } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
      }
    },
  },
  beforeRouteUpdate(to, from, next) {
    // Проверяем валидность даты
    if (!this.isValidDate(to.params.date)) {
      next({
        name: 'movies',
        params: { date: this.getDefaultDate() }
      });
      return;
    }
    
    this.selectedDate = to.params.date;
    this.fetchMovies();
    next();
  },
  mounted() {
    document.body.classList.add('page-client');
    console.log('Текущая дата:', this.date, typeof this.date);
    
    // Преобразуем строковую дату в объект Date
    this.selectedDate = this.formatDate(this.isValidDate(this.date) ? new Date(this.date) : new Date());
    this.generateDaysOfWeek();
    this.fetchMovies();

    // Проверяем валидность даты
    if (!this.isValidDate(this.selectedDate)) {
      this.selectedDate = this.getCurrentDate();
    }
    this.fetchData();
    console.log('Текущая дата:', this.date, typeof this.date);

    this.startPolling(); // обновляет информацию о залах, кино, сеансах
    this.getMovies();
    this.getSessions();
    this.getHalls();

    // Получаем QR-код с сервера  
    // fetch('http://127.0.0.1:8000/get-qr-code')
    // .then(res => res.json())
    // .then(data => {
    //   this.qrCodeData = data.qr_code; 
    // })
    // .catch(error => {
    //     console.error(error);
    //   });
  }
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>

  <!-- <Head title="Welcome">
    <link rel="preconnect" href="https://rsms.me/" />
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
  </Head> -->

  <!-- <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
    <nav class="flex items-center justify-end gap-4 movie-seances__time">
      <Link v-if="$page.props.auth.user" :href="route('dashboard')"
        class="movie-seances__time inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
      Dashboard
      </Link>
      <template v-else>
        <Link :href="route('login')"
          class="movie-seances__time inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]">
        Log in
        </Link>
        <Link :href="route('register')"
          class="movie-seances__time inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]">
        Register
        </Link>
      </template>
    </nav>
  </header> -->

  <!-- <Link :href="route('login')"
    class="movie-seances__time link_color">
  Log in login Link
  </Link> -->
  <!-- <Link :href="route('register')"
    class="movie-seances__time link_color">
  Register Link
  </Link> -->
  

  <nav class="page-nav">
    <router-link 
      v-for="(day, index) in daysOfWeek" 
      :key="index" 
      class="page-nav__day"
      :to="{ name: 'Index', params: { date: day.date } }"
      :class="{
        'page-nav__day_today': isToday(day.date),
        'page-nav__day_chosen': selectedDate === day.date,
        'page-nav__day_weekend': isWeekend(day.date),        
      }"
      @click.native="handleDateClick(day.date)"
    >
      <span class="page-nav__day-week">{{ day.weekday }}</span>
      <span class="page-nav__day-number">{{ day.day }}</span>
    </router-link>
  </nav>

  
  <router-link :to="{ name: 'Admin' }" class="link_color">
    Login for Admin
  </router-link>
  <router-link :to="{ name: 'Login' }" class="link_color">
    Login for Login
  </router-link>
  <main>

    <section v-for="movie in movies" :key="movie.id" class="movie">
      <div class="movie__info">
        <div class="movie__poster">
          <img class="movie__poster-image" alt="Звёздные войны постер" :src=movie?.image_url>
        </div>
        <div class="movie__description">
          <h2 class="movie__title">{{ movie?.title }}</h2>
          <p class="movie__synopsis">{{ movie?.description }}</p>
          <p class="movie__data">
            <span class="movie__data-duration">{{ movie?.duration }} минут </span>
            <span class="movie__data-origin">{{ movie?.country }}</span>
          </p>
        </div>
      </div>

      <div v-if="filteredMovies.length > 0">
        <div v-for="hall in halls" :key="hall.id" class="movie-seances__hall">
          <h3 class="movie-seances__hall-title">Зал {{ hall.id }} {{ hall.name }}</h3>
  
          <div v-if="globalSalesOpen" >
            <ul class="movie-seances__list">          
              <li v-for="session in sessions" :key="session.id" class="movie-seances__time-block">
                <router-link v-if="session.movie_id === movie.id && session.hall_id === hall.id" 
                  :to="{ name: 'Hall', params: { hallId: hallId, sessionId: session.id } }" 
                  class="movie-seances__time">
                  {{ formatTime(session.start_datetime) }}
                </router-link>
              </li>
            </ul>
          </div>
          <div v-if="!globalSalesOpen" >
            <h2>Продажи {{ globalSalesOpen ? 'открыты' : 'закрыты' }}</h2>
          </div>        
        </div>
      </div>
      <div v-else>
        Сеансов на выбранную дату нет.
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

/* ~~~~~~~~~~~~ Главная ~~~~~~~~~~~~ */
.page-nav {
  position: sticky;
  top: 2px;
  padding-bottom: 1rem;
  display: flex;
  flex-wrap: nowrap;
  align-items: stretch;
  color: #000000;
  z-index: 10;
}

.page-nav .page-nav__day {
  flex-basis: calc(100% / 8);
  font-size: 1.2rem;
  padding: 1rem;
  background: rgba(255, 255, 255, 0.9);
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.24), 0px 0px 2px rgba(0, 0, 0, 0.12);
  border-radius: 2px;
  text-decoration: none;
  color: #000000;
}

.page-nav .page-nav__day+.page-nav__day {
  margin-left: 1px;
}

.page-nav .page-nav__day .page-nav__day-week::after {
  content: ',';
}

.page-nav .page-nav__day .page-nav__day-number::before {
  content: ' ';
}

.page-nav .page-nav__day .page-nav__day-week,
.page-nav .page-nav__day .page-nav__day-number {
  display: block;
}

.page-nav .page-nav__day_chosen {
  flex-grow: 1;
  background-color: #FFFFFF;
  font-weight: 700;
  transform: scale(1.1);
}

.page-nav .page-nav__day_weekend {
  color: #DE2121;
}

.page-nav .page-nav__day_next {
  text-align: center;
}

.page-nav__day:hover {
  background-color: #f8f9fa;
}

.page-nav .page-nav__day_next::before {
  content: '>';
  font-family: monospace;
  font-weight: 700;
  font-size: 2.4rem;
}

.page-nav .page-nav__day_today .page-nav__day-week::before {
  content: 'Сегодня';
  display: block;
}

.page-nav .page-nav__day_today .page-nav__day-week,
.page-nav .page-nav__day_today .page-nav__day-number {
  display: inline;
}

.movie {
  position: relative;
  padding: 1.5rem;
  margin-top: 3rem;
  background: rgba(241, 235, 230, 0.95);
  color: #000000;
}

.movie .movie__info {
  display: flex;
}

.movie .movie__poster {
  position: relative;
  width: 12.5rem;
  height: 17.5rem;
  /*    left: 1.5rem;
      top: -1.5rem;*/
}

.movie .movie__poster .movie__poster-image {
  position: relative;
  top: -3rem;
  width: 12.5rem;
  height: 17.5rem;
}

.movie .movie__poster::after {
  content: '';
  display: block;
  position: absolute;
  right: -0.7rem;
  top: -3rem;
  border: 1.5rem solid transparent;
  border-bottom: 0 solid transparent;
  border-right: 0 solid transparent;
  border-left: 0.7rem solid #772720;
}

.movie .movie__description {
  flex-grow: 1;
  padding-left: 1.5rem;
}

.movie .movie__title {
  font-weight: 700;
  font-size: 1.6rem;
}

.movie .movie__synopsis {
  font-size: 1.4rem;
  margin-top: 1rem;
}

.movie .movie__data {
  font-size: 1.4rem;
  font-weight: 300;
  margin-top: 1rem;
}

.movie .movie-seances__hall+.movie-seances__hall {
  margin-top: 2rem;
}

.movie .movie-seances__hall .movie-seances__hall-title {
  font-weight: 700;
  font-size: 1.6rem;
}

.movie .movie-seances__hall .movie-seances__list {
  margin-top: 0.7rem;
  font-size: 0;
}

.movie .movie-seances__hall .movie-seances__time-block {
  display: inline-block;
  margin-bottom: 4px;
}

.movie .movie-seances__hall .movie-seances__time-block:nth-last-of-type(n + 1) {
  margin-right: 4px;
}

.movie .movie-seances__hall .movie-seances__time {
  display: block;
  padding: 8px;
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.24), 0px 0px 2px rgba(0, 0, 0, 0.12);
  border-radius: 2px;
  background-color: #FFFFFF;
  color: #000000;
  text-decoration: none;
  font-size: 1.5rem;
}

.link_color {  
  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.24), 0px 0px 2px rgba(0, 0, 0, 0.12);
  border-radius: 2px;
  background-color: #FFFFFF;
  color: #000000;
  text-decoration: none;
  font-size: 1.3rem;
  padding: 2px;
  margin: 10px;  
  align-items: center;    
}

.link_color:hover, .link_color:active {
  background-color: #000000;
  color: #FFFFFF;
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