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
      selectedDate: this.getCurrentDate(),
      daysOfWeek: [],
      filteredMovies: [],
      auth: false,
    }
  },  
  methods: {
    // получение всех фильмов
    async getMovies() {
      try {        
        const response = await axios.get('http://127.0.0.1:8000/movies/');
        if (Array.isArray(response.data)) {
          this.movies = response.data;
        } else {
          throw new Error('Неверный формат данных');
        }
      } catch (error) {
        console.error('Ошибка при получении фильмов:', error);
      }
    },
    // получение всех сеансов
    async getSessions() {
      try {
        const response = await axios.get('http://127.0.1:8000/sessions');
        if (Array.isArray(response.data)) {
          this.sessions = response.data.filter(session => {
            // Проверяем, что дата в сессии валидна
            return !isNaN(new Date(session.start_datetime).getTime());
          });
        } else {
          throw new Error('Неверный формат данных');
        }
      } catch (error) {
        console.error('Ошибка при получении сеансов:', error);
      }
    },
    // получение всех Hall
    async getHalls() {
      try {
        const response = await axios.get('http://127.0.1:8000/hall/index');
        if (Array.isArray(response.data)) {
          this.halls = response.data;
        } else {
          throw new Error('Неверный формат данных');
        }
      } catch (error) {
        console.error('Ошибка при получении залов:', error);
      }
    },
    // периодическое обновление залов, кино, сессий
    startPolling() {
      this.pollInterval = setInterval(() => {
        if (!this.isFetching) {
          this.fetchData()
                .catch(() => {
                    console.error('Ошибка при обновлении данных');
                });
        }
      }, 5000); // Каждые 5 секунд
    },
    stopPolling() {
      // Очищаем интервал
      clearInterval(this.pollInterval);
    },
    // async fetchHalls() {
    //   try {
    //     // Проверяем, не выполняется ли уже запрос
    //     if (this.isFetching) return;
    //     if (!this.isValidDate(this.date)) {
    //       // this.date = this.getDefaultDate();
    //       this.date = this.getCurrentDate();
    //     }
        
    //     this.isFetching = true;
        
    //     // Обновляем данные
    //     await Promise.all([
    //       this.getMovies(),
    //       this.getSessions(),
    //       this.getHalls()
    //     ]);
    //     // console.log("fetchHalls Успешное обновление!");
        
    //     await this.filterMoviesByDate(this.selectedDate);
    //   } catch (error) {
    //     console.error('Ошибка при получении данных:', error);
    //   } finally {
    //     this.isFetching = false;
    //   }
    // },

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
        const today = new Date();
        const days = [];

        // Генерируем даты от текущей до +6 дней
        for (let i = 0; i < 7; i++) {
          const date = new Date(today);
          date.setDate(date.getDate() + i); // добавляем i дней к текущей дате
          
          days.push({
            date: this.formatDate(date),
            weekday: this.getWeekday(date),
            day: date.getDate(),
            month: date.getMonth() + 1,
            year: date.getFullYear(),
            isToday: this.isSameDay(date, today)
          });
        }

        // Находим дату понедельника текущей недели
      //  const startDate = new Date(today);
        // startDate.setDate(startDate.getDate() - startDate.getDay());
        
      //  const dayOfWeek = startDate.getDay();
        

        // Вычисляем дату понедельника текущей недели
        // Если сегодня воскресенье (0), то нужно отнять 6 дней
        // Если сегодня понедельник (1), то отнимать ничего не нужно
        // startDate.setDate(startDate.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
      //  startDate.setDate(startDate.getDate() + dayOfWeek);
                
        
        
        // for (let i = 0; i < 7; i++) {
        //   const date = new Date(startDate);
        //   date.setDate(date.getDate() + i);
        //   days.push({
        //     date: this.formatDate(date),
        //     weekday: this.getWeekday(date),
        //     day: date.getDate(),
        //     month: date.getMonth() + 1,
        //     year: date.getFullYear(),
        //     isToday: this.isSameDay(date, today) // может и не нужна
        //   });
        // }
        
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
        //return new Date().toISOString().split('T')[0];
        return this.getCurrentDate();
      }

      return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
    },
    getWeekday(date) {
      const weekdays = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', ];
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
        // Преобразуем дату в строку
        const formattedDate = String(date).trim();
        
        // Проверяем формат
        if (/^\d{4}-\d{2}-\d{2}$/.test(formattedDate)) {
          this.$router.push({
            name: 'Index',
            state: { 
              date: formattedDate 
            }
          });
        
        this.selectedDate = formattedDate;
        this.filterMoviesByDate(formattedDate);
        this.fetchData();
        } else {
          console.error('Неверный формат даты:', date);
        }

      } catch (error) {
        console.error('Ошибка при выборе даты:', error);
      }
    },
    async filterMoviesByDate(date) {
      try {
        // Проверяем формат даты
        if (!/^\d{4}-\d{2}-\d{2}$/.test(date)) {
          throw new Error('Неверный формат даты');
        }

        // Преобразуем дату в начало и конец дня
        const startOfDay = new Date(date);
        const endOfDay = new Date(date);
        endOfDay.setHours(23, 59, 59);

        // Убедимся, что даты корректны
        if (isNaN(startOfDay.getTime()) || isNaN(endOfDay.getTime())) {
          throw new Error('Некорректная дата');
        }

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
      // let a = this.formatDate(new Date());
      // console.log('getCurrentDate a Текущая дата:', a, typeof a);
      // return a;
      const date = new Date();
      return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
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
    isValidDate(date) {
      // return /^\d{4}-\d{2}-\d{2}$/.test(dateString) && !isNaN(new Date(dateString).getTime()); // добавил .getTime()

      return (
        typeof date === 'string' &&
        /^\d{4}-\d{2}-\d{2}$/.test(date) &&
        !isNaN(new Date(date).getTime())
      );
    },
    getDefaultDate() {
      return new Date().toISOString().split('T')[0];
    },
    async fetchData() {
      try {
        if (!this.isValidDate(this.selectedDate)) {
          this.selectedDate = this.getCurrentDate();
        }
        this.isFetching = true;

        await Promise.all([
          this.getMovies(),
          this.getSessions(),
          this.getHalls()
        ]);
        await this.filterMoviesByDate(this.selectedDate);
      } catch (error) {
        console.error('Ошибка при загрузке данных:', error);
      } finally {
        this.isFetching = false;
      }
    },
    logRoute(routeName) {
      console.log(`Переход по маршруту: ${routeName}`);

      //this.changeUserState();
    },
    changeUserState (){
      if (this.auth) {
        localStorage.removeItem('auth');
        this.$router.push({ name: 'Index'});
      } else {
        localStorage.setItem('auth', true);
        this.auth = true;
      }
    },
    updateBodyClasses(type) {
      if (type === 'admin') {
        document.body.classList.remove('page-client');
        document.body.classList.remove('page-client-index');
        document.body.classList.add('page-admin');
        document.body.classList.add('page-admin-index');
      } else if (type === 'client') {
        document.body.classList.remove('page-admin');
        document.body.classList.remove('page-admin-index');
        document.body.classList.add('page-client');
        document.body.classList.add('page-client-index');
      }
    },
  },
  beforeRouteUpdate(to, from, next) {
    console.log('beforeRouteUpdate: new date', to.params.date);

    // Проверяем валидность даты
    if (!this.isValidDate(to.params.date)) {
      next({
        name: 'MoviesByDate',
        params: { date: this.getDefaultDate() }
      });
      return;
    }
    
    this.selectedDate = to.params.date;
    console.log('beforeRouteUpdate this.selectedDate', this.selectedDate);
    this.fetchMovies();
    next();
  },
  beforeRouteEnter(to, from, next) {
    // защита от некорректных параметров
    // const date = to.params.date;
    // Проверяем формат даты
    // if (!/^\d{4}-\d{2}-\d{2}$/.test(date) || isNaN(new Date(date).getTime())) {
    //   const currentDate = new Date().toISOString().split('T')[0];
    //   return next({
    //     name: 'MoviesByDate',
    //     params: { date: currentDate }
    //   });
    // }
    
    // Если дата валидная - продолжаем навигацию
    // next();

  //   document.body.classList.remove('page-admin');
  //   document.body.classList.remove('page-admin-index');
  //   document.body.classList.add('page-client');
  //   next();
  
  },
  beforeEnter(to, from, next) {
    const date = to.params.date;
    
    if (typeof date === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(date)) {
      next();
    } else {
      const currentDate = this.getCurrentDate();
      next({
        name: 'Index',
        params: { date: currentDate }
      });
    }
    
  },
  watch: {
    // selectedDate(newDate) {
    //   console.log('selectedDate изменилась:', newDate);
    //   this.filterMoviesByDate(newDate);
    // },
    $route() {
      if (this.$route.meta.isAdmin) {
        this.updateBodyClasses('admin');
      } else {
        this.updateBodyClasses('client');
      }
    }    
  },
  mounted() {
    document.body.classList.add('page-client');
    document.body.classList.add('page-client-index');
    //this.date = this.$route.params.date;

    // console.log('mounted: selectedDate', this.selectedDate);
    // console.log('mounted: date from route', this.$route.params.date);

    // Если дата пришла через пропс или маршрут - используем её
    // if (this.date) {
    //   this.selectedDate = this.date;
    // } else if (this.$route.params.date) {
    //   this.selectedDate = this.$route.params.date;
    // }
    
    // console.log('mounted: selectedDate после установки', this.selectedDate);
    
    // // Проверяем валидность
    // if (!this.isValidDate(this.selectedDate)) {
    //   this.selectedDate = this.getCurrentDate();
    // }

    this.selectedDate = this.getCurrentDate();
    
    this.generateDaysOfWeek();
    this.fetchData();
    this.startPolling();
  }
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>

  <nav class="page-nav">
    <router-link 
      v-for="(day, index) in daysOfWeek" 
      :key="index" 
      class="page-nav__day"
      :to="{ name: 'Index', state: { date: day.date } }"
      :class="{
        'page-nav__day_today': isToday(day.date),
        'page-nav__day_chosen': selectedDate === day.date,
        'page-nav__day_weekend': isWeekend(day.date),        
      }"
      @click="handleDateClick(day.date)"
    >
      <span class="page-nav__day-week">{{ day.weekday }}</span>
      <span class="page-nav__day-number">{{ day.day }}</span>
    </router-link>
  </nav>
  
  <nav class="page-nav">  
    <!-- <router-link :to="{ name: 'Admin' }" class="link_color" @click.native="logRoute('Admin')">
      Вход в административную панель
    </router-link> -->
    <!-- альтернативная кнопка перехода -->
    <a 
      href="/admin" 
      class="link_color" 
      @click="logRoute('Admin')"
    >
      Вход в административную панель
    </a>
    <!-- <router-link :to="{ name: 'Login' }" class="link_color" @click.native="logRoute('Login')">
      Login for Login
    </router-link> -->
  </nav>

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
      <router-view></router-view>

      <div v-if="filteredMovies.length > 0">
        <div v-for="hall in halls" :key="hall.id" class="movie-seances__hall">
          <h3 class="movie-seances__hall-title">Зал {{ hall.id }} {{ hall.name }}</h3>
  
          <div v-if="globalSalesOpen" >
            <ul class="movie-seances__list">          
              <li v-for="session in sessions" :key="session.id" class="movie-seances__time-block">
                <router-link 
                  v-if="session.movie_id === movie.id && session.hall_id === hall.id" 
                  :to="{ 
                    name: 'Hall', 
                    params: { hallId: hall.id, sessionId: session.id } 
                  }"
                  @click="logRoute('Hall')" 
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
  background-color: rgba(0, 0, 0, 0);
  background-size: cover;
  background-attachment: fixed;
  background-position: right; 
  background-blend-mode: multiply;  
  counter-reset: num;
}

body.page-client-index {
  height: 100%;
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