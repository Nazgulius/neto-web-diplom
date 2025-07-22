<script>
import { Head, Link } from '@inertiajs/vue3';
// import { Link } from '@inertiajs/inertia-vue3';

export default {
  name: 'Index',
  components: {
    Head,
    Link,
  },

  data() {
    return { // тут состояние 
    }
  },
  methods: {
    // методы для бронирования 
  },
  mounted() {
    // fetch данных о зале 
  }
}


</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
  </header>
  <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    
      
          <header class="mb-6 w-full max-w-[335px] text-sm not-has-[nav]:hidden lg:max-w-4xl">
            <nav class="flex items-center justify-end gap-4">
                <Link
                    v-if="$page.props.auth.user"
                    :href="route('dashboard')"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >
                    Dashboard
                </Link>
                <template v-else>
                    <Link
                        :href="route('login')"
                        class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#EDEDEC] dark:hover:border-[#3E3E3A]"
                    >
                        Log in
                    </Link>
                    <Link
                        :href="route('register')"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                    >
                        Register
                    </Link>
                </template>
            </nav>
        </header>
      
  
  
  
  <nav class="page-nav">
    <a class="page-nav__day page-nav__day_today" href="#">
      <span class="page-nav__day-week">Пн</span><span class="page-nav__day-number">31</span>
    </a>
    <a class="page-nav__day" href="#">
      <span class="page-nav__day-week">Вт</span><span class="page-nav__day-number">1</span>
    </a>
    <a class="page-nav__day page-nav__day_chosen" href="#">
      <span class="page-nav__day-week">Ср</span><span class="page-nav__day-number">2</span>
    </a>
    <a class="page-nav__day" href="#">
      <span class="page-nav__day-week">Чт</span><span class="page-nav__day-number">3</span>
    </a>
    <a class="page-nav__day" href="#">
      <span class="page-nav__day-week">Пт</span><span class="page-nav__day-number">4</span>
    </a>
    <a class="page-nav__day page-nav__day_weekend" href="#">
      <span class="page-nav__day-week">Сб</span><span class="page-nav__day-number">5</span>
    </a>
    <a class="page-nav__day page-nav__day_next" href="#">
    </a>
  </nav>
  
  <main>
    <section class="movie">
      <div class="movie__info">
        <div class="movie__poster">
          <img class="movie__poster-image" alt="Звёздные войны постер" src="/src/client/poster1.jpg">
        </div>
        <div class="movie__description">
          <h2 class="movie__title">Звёздные войны XXIII: Атака клонированных клонов</h2>
          <p class="movie__synopsis">Две сотни лет назад малороссийские хутора разоряла шайка нехристей-ляхов во главе с могущественным колдуном.</p>
          <p class="movie__data">
            <span class="movie__data-duration">130 минут</span>
            <span class="movie__data-origin">США</span>
          </p>
        </div>
      </div>  
      
      <div class="movie-seances__hall">
        <h3 class="movie-seances__hall-title">Зал 1</h3>
        <ul class="movie-seances__list">
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">10:20</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:10</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:40</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">22:00</a></li>
        </ul>
      </div>
      <div class="movie-seances__hall">
        <h3 class="movie-seances__hall-title">Зал 2</h3>
        <ul class="movie-seances__list">
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">11:15</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:40</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">16:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:30</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">21:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">23:30</a></li>     
        </ul>
      </div>      
    </section>
    
    <section class="movie">
      <div class="movie__info">      
        <div class="movie__poster">
          <img class="movie__poster-image" alt="Альфа постер" src="/src/client/poster2.jpg">
        </div>
        <div class="movie__description">        
          <h2 class="movie__title">Альфа</h2>
          <p class="movie__synopsis">20 тысяч лет назад Земля была холодным и неуютным местом, в котором смерть подстерегала человека на каждом шагу.</p>
          <p class="movie__data">
            <span class="movie__data-duration">96 минут</span>
            <span class="movie__data-origin">Франция</span>
          </p>
        </div>    
      </div>  
      <div class="movie-seances__hall">
        <h3 class="movie-seances__hall-title">Зал 1</h3>
        <ul class="movie-seances__list">
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">10:20</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:10</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:40</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">22:00</a></li>
        </ul>
      </div>
      <div class="movie-seances__hall">
        <h3 class="movie-seances__hall-title">Зал 2</h3>
        <ul class="movie-seances__list">
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">11:15</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:40</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">16:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:30</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">21:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">23:30</a></li>     
        </ul>
      </div>      
    </section>   
    
    <section class="movie">
      <div class="movie__info">      
        <div class="movie__poster">
          <img class="movie__poster-image" alt="Хищник постер" src="/src/client/poster2.jpg">
        </div>
        <div class="movie__description">        
          <h2 class="movie__title">Хищник</h2>
          <p class="movie__synopsis">Самые опасные хищники Вселенной, прибыв из глубин космоса, высаживаются на улицах маленького городка, чтобы начать свою кровавую охоту. Генетически модернизировав себя с помощью ДНК других видов, охотники стали ещё сильнее, умнее и беспощаднее.</p>
          <p class="movie__data">
            <span class="movie__data-duration">101 минута</span>
            <span class="movie__data-origin">Канада, США</span>
          </p>
        </div>    
      </div>  
      <div class="movie-seances__hall">
        <h3 class="movie-seances__hall-title">Зал 1</h3>
        <ul class="movie-seances__list">
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">09:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">10:10</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">12:55</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:15</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">14:50</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">16:30</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:00</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">18:50</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">19:50</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">20:55</a></li>
          <li class="movie-seances__time-block"><a class="movie-seances__time" :href="route('hall')">22:00</a></li>
        </ul>
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

body {
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