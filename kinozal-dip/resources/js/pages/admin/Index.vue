<script>
import { Head, Link } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';

export default {
  name: 'IndexAdmin',
  props: {
    hallId: {
      type: Number,
      required: false,
      default() { return 5; }
    },
    sessionId: {
      type: String,
      required: false,
      default() { return 'abc123'; }
    }
  },
  data() {
    return {
      halls: [], // залы
      selectedHall: null, // выбранный зал
      hallConfig: {
        rows: 6, // количество рядов по умолчанию
        seatsPerRow: 6, // количество мест по умолчанию
        seats: [], // массив с конфигурацией кресел
        isGenerating: false, // Флаг для отслеживания генерации
      },
      seatTypes: ['standart', 'vip', 'disabled'],
      currentSeatType: 'standart', // тип кресла по умолчанию
      loading: false,
      error: null,
      prices: {
        standart: 10, // цена за обычные места
        vip: 20 // цена за VIP места
      },
      movies: [],
      sessions: [],
      globalSalesOpen: true,
      editMovieID: null,
      timelineStart: '09:00', // например
      timelineEnd: '23:00', // например
      timelineWidth: 720, // ширина шкалы в пикселях
      formHallData: {
        name: '',
        rows: 10,
        seats_per_row: 10,
        amountStandart: 0,
        amountVip: 0,
        active: true,
      }, // содержит данные для сохранения нового Hall
      popupHidden: true, // попап скрыт по умолчанию
      popupHiddenAM: true, // попап скрыт по умолчанию
      popupHiddenEM: true, // попап скрыт по умолчанию
      popupHiddenAddSessionMovie: true, // попап скрыт по умолчанию
      formMovieData: {
        title: '', // название
        description: '', // описание
        duration: 0, // минуты
        country: '', // страна
        image_url: "/src/client/poster1.jpg", // пока как заглушка
      },
      formMovieSessionData: {
        movie_id: 0,
        hall_id: 0,
        start_time: '',
      },
    }
  },
  created() {
    this.fetchStatus();
    this.fetchHalls();
  },
  computed: {
    totalTimelineMinutes() {
      return this.timeToMinutes(this.timelineEnd) - this.timeToMinutes(this.timelineStart);
    },
    // можно вернуть ширину на основe CSS

    // currentSession() {
    //   return this.sessionsOpen.find(s => s.id === this.activeSessionId) || null;
    // },
    isValid() {
      return (
        this.selectedHall &&
        this.hallConfig.rows > 0 &&
        this.hallConfig.seatsPerRow > 0 &&
        this.hallConfig.seats.length > 0
      );

    },
    totalSeats() {
      return this.hallConfig.rows * this.hallConfig.seatsPerRow;
    },
  },
  methods: {
    computeLeft(start_time) {
      const delta = this.minutesBetween(this.timelineStart, start_time);
      const percent = delta / this.totalTimelineMinutes; // ограничиваем от 0 до timelineWidth 
      return Math.max(0, Math.min(this.timelineWidth, percent * this.timelineWidth));
    },
    // вкл/выкл попап зала, добавления и редактирования кино
    toglePopupHall() {
      this.popupHidden = !this.popupHidden;
    },
    toglePopupAddMovie() {
      this.popupHiddenAM = !this.popupHiddenAM;
    },
    // тогл для открытия редактирования
    toglePopupEditMovie(movieID) {
      this.popupHiddenEM = !this.popupHiddenEM;
      this.editMovieID = movieID;
    },
    // тогл для закрытия редактирования
    toglePopupEditMovieClose() {
      this.popupHiddenEM = !this.popupHiddenEM;
    },
    toglePopupAddSessionMovie() {
      this.popupHiddenAddSessionMovie = !this.popupHiddenAddSessionMovie;
    },
    // добавление Hall 
    submitFormHalls() {
      axios.post('http://127.0.0.1:8000/hall/create', this.formHallData)
        .then(response => {
          console.log('Успех:', response.data);
          this.getHalls(); // Перезагружаем список залов
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });
    },
    // удаление Hall
    btnHallDel(hallID) {
      axios.delete('http://127.0.0.1:8000/hall/destroy/' + hallID)
        .then(response => {
          // console.log('Успех:', response.data);
          // удалить из локального списка
          this.halls = this.halls.filter(h => h.id !== hallID);
          console.log('Зал удалён, список обновлён локально');
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });
    },
    // добавление кино
    submitFormAddMovie() {
      axios.post('http://127.0.0.1:8000/movies/create', this.formMovieData)
        .then(response => {
          console.log('Успех:', response.data);
          this.getMovies(); // Перезагружаем список залов
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });
    },
    // удаление кино
    btnMovieDel(movieID) {
      this.toglePopupAddSessionMovie(); // закрывает форму
      axios.delete('http://127.0.0.1:8000/movie/destroy/' + movieID)
        .then(response => {
          // удалить из локального списка
          this.movies = this.movies.filter(h => h.id !== movieID);
          console.log('Кино удалено, список обновлён локально');
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });
    },
    //редактирование кино
    submitFormEditMovie() {
      // Проверяем, что форма заполнена
      if (!this.formMovieData.title || !this.formMovieData.duration) {
        alert('Заполните все обязательные поля');
        return;
      }
      console.log('submitFormEditMovie editMovieID ', this.editMovieID);

      // Проверяем, что editMovieID существует и является числом
      if (!this.editMovieID || isNaN(this.editMovieID)) {
        console.error('Неверный ID фильма:', this.editMovieID);
        return;
      }

      // axios.post('http://127.0.0.1:8000/movies/update/' + movie.id, this.formMovieData)
      axios.post(`http://127.0.0.1:8000/movies/update/${this.editMovieID}`, this.formMovieData)
        .then(response => {
          console.log('Успех:', response.data);
          this.getMovies(); // Перезагружаем список залов
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });

    },
    // создание сессии кино
    submitFormAddSessionMovie() {
      console.log('submitFormAddSessionMovie start');
      this.formMovieSessionData.movie_id = this.editMovieID + 1;
      this.formMovieSessionData.hall_id = Number(this.formMovieSessionData.hall_id) || 0;
      console.log('this.formMovieSessionData: ', this.formMovieSessionData);

      axios.post('http://127.0.0.1:8000/movies/session/create', this.formMovieSessionData)
        .then(response => {
          console.log('Успех:', response.data);
          this.getSessions(); // Перезагружаем список сессий
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });
    },
    // удаление сессии кино
    btnSessionDel(sessionID) {
      axios.delete('http://127.0.0.1:8000/movies/session/destroy/' + sessionID)
        .then(response => {
          // удалить из локального списка
          this.sessions = this.sessions.filter(h => h.id !== sessionID);
          console.log('Сессия кино удалена, список обновлён локально');
        })
        .catch(error => {
          if (error.response) {
            // Ответ сервера с кодом ошибки
            console.error('Ошибка сервера:', error.response.data);
          } else {
            console.error(error);
          }
        });

    },
    updateRows(event) {
      // const value = parseInt(event.target.value);
      if (!isNaN(value) && value > 0) {
        this.hallConfig.rows = value;
        this.resetSeats(); // Очищаем старые данные
        // this.updateSeatsLayout();
        this.$nextTick(() => {
          this.generateSeats();
          // this.updateSeatsLayout();
        });
      }
    },
    updateSeatsPerRow(event) {
      const value = parseInt(event.target.value);
      if (!isNaN(value) && value > 0) {
        this.hallConfig.seatsPerRow = value;
        this.resetSeats(); // Очищаем старые данные
        // this.updateSeatsLayout();
        this.$nextTick(() => {
          this.generateSeats();
          // this.updateSeatsLayout();
        });
      }
    },
    resetSeats() {
      this.seats = [];
      // Дополнительно можно очистить другие связанные данные
    },
    // метод для отризоваки сетки зала по ручному вводу рядов и мест
    generateSeats() {
      // this.hallConfig.seats = []; // Очищаем массив перед генерацией

      const seats = [];
      for (let row = 1; row <= this.hallConfig.rows; row++) {
        const rowSeats = [];
        for (let seat = 1; seat <= this.hallConfig.seatsPerRow; seat++) {
          rowSeats.push({
            row,
            seat,
            type: 'standart'
          });
        }
        seats.push(rowSeats);
      }
      this.hallConfig.seats = seats;

    },
    // Новый метод для обновления рассадки автоматичнский
    updateSeatsLayout() {
      // Если есть сохраненные данные из API, используем их
      if (
        this.hallConfig &&
        this.hallConfig.seats &&
        (this.hallConfig.seats.length > 0 || Object.keys(this.hallConfig.seats).length > 0)
      ) {
        // console.log("updateSeatsLayout this.hallConfig ", this.hallConfig);
        this.hallConfig.seats = this.formatSeats(this.hallConfig.seats);
      } else {
        // console.log("updateSeatsLayout 1 вариант не прошёл, это стандартная сетка ", this.hallConfig.seats);
        // Если данных нет, генерируем стандартную рассадку
        this.hallConfig.seats = this.generateDefaultSeats();
      }
      
    },
    // Метод для генерации стандартной рассадки
    generateDefaultSeats() {
      const seats = [];
      for (let row = 1; row <= this.hallConfig.rows; row++) {
        const rowSeats = [];
        for (let seat = 1; seat <= this.hallConfig.seatsPerRow; seat++) {
          rowSeats.push({
            row,
            seat,
            type: 'standart'
          });
        }
        seats.push(rowSeats);
      }
      return seats;
    },
    selectSeatType(type) {
      this.currentSeatType = type;
    },
    changeSeatType(seat) {
      const currentIndex = this.seatTypes.indexOf(seat.type);
      const nextIndex = (currentIndex + 1) % this.seatTypes.length;
      seat.type = this.seatTypes[nextIndex];
      // находим индекс ряда
      // const rowIndex = this.hallConfig.seats.findIndex(row => 
      //   row.find(s => s.number === seat.number && s.row === seat.row)
      // );
      
      // // находим индекс места в ряду
      // const seatIndex = this.hallConfig.seats[rowIndex].findIndex(s => 
      //   s.number === seat.number && s.row === seat.row
      // );
      
      // // меняем тип места
      // this.hallConfig.seats[rowIndex][seatIndex].type = this.currentSeatType;
    },
    btnCenselHallSeats() {
      console.log('Cansel Hall seats');
      this.hallConfig = {
        rows: 10,
        seatsPerRow: 8,
        seats: []
      };
      this.currentSeatType = 'standart';
      // this.generateSeats();
      this.updateSeatsLayout();
    },
    btnSaveHallSeats() {
      console.log('Save Hall seats');
      if (!this.isValid) {
        alert('Некорректные параметры зала');
        return;
      }

      // Подготовка данных для отправки
      const seatsData = {
        hall_id: this.selectedHall,
        rows: this.hallConfig.rows,
        seats_per_row: this.hallConfig.seatsPerRow,
        seats: this.hallConfig.seats.map(row =>
          row.map(seat => ({
            row: seat.row,
            seat: seat.seat,
            type: seat.type
          }))
        )
      };

      axios.post('http://127.0.0.1:8000/halls/hall/update-seats', seatsData)
        .then(response => {
          alert('Конфигурация зала сохранена!');
        })
        .catch(error => {
          console.error('Ошибка при сохранении конфигурации:', error);
          alert('Произошла ошибка при сохранении конфигурации');
        });
    },
    async fetchHalls() {
      try {
        const response = await axios.get('http://127.0.0.1:8000/hall/index');
        this.halls = response.data;

        // Устанавливаем первый зал как выбранный по умолчанию
        if (this.halls.length > 0) {
          this.selectedHall = this.halls[0].id;
          await this.loadHallConfig(this.selectedHall);
        }
      } catch (error) {
        console.error('Ошибка при загрузке залов:', error);
      }
    },
    async loadHallConfig(hallId) {
      try {
        const response = await axios.get(`http://127.0.0.1:8000/halls/${hallId}/config`);
        // console.log("loadHallConfig response ", response);
        this.hallConfig = {
          rows: response.data.rows,
          seatsPerRow: response.data.seats_per_row,
          // seats: this.generateSeats(response.data.seats)
          // seats: response.data.seats
          // seats: this.formatSeats(response.data.seats) // Используем форматирование
          seats: response.data.seats || {} // Добавляем проверку на существование
        };
        // console.log('loadHallConfig дошли до updateSeatsLayout');
        this.updateSeatsLayout(); // Обновляем рассадку после загрузки
      } catch (error) {
        console.error('Ошибка при загрузке конфигурации зала:', error);
        if (error.response) {
          console.log('Статус ошибки:', error.response.status);
          console.log('Сообщение об ошибке:', error.response.data);
        }
      }
    },
    formatSeats(apiSeats) {
      // console.log('formatSeats apiSeats ', apiSeats);
      // console.log('formatSeats this.hallConfig.seatsPerRow ', this.hallConfig.seatsPerRow);

      // Добавляем проверку корректности входных данных
      if (!apiSeats || typeof apiSeats !== 'object') {
        console.error('Некорректные входные данные apiSeats');
        return [];
      }
      
      if (!this.hallConfig.rows || !this.hallConfig.seatsPerRow) {
        console.error('Некорректная конфигурация зала');
        return [];
      }

      if (!apiSeats || Object.keys(apiSeats).length === 0) {
        return []; // Возвращаем пустой массив, если данные отсутствуют
      }
      // Преобразуем плоские данные в вложенный массив
      const seats = [];

      for (let i = 1; i <= this.hallConfig.rows; i++) {
        const rowSeats = [];

        for (let j = 1; j <= this.hallConfig.seatsPerRow; j++) {
          const seatKey = this.getSeatKey(i, j);

          // Добавляем логирование для отладки
          // console.log(`Проверяем ключ: ${seatKey}`);
          // console.log(`Значение по ключу:`, apiSeats[seatKey]);

          const seat = apiSeats[seatKey];

          // Добавляем проверку на существование места
          if (seat) {
            rowSeats.push({
              row: seat.row,
              number: seat.number,
              type: seat.type || 'standart' // Устанавливаем тип по умолчанию
            });
          } else {
            console.warn(`Место не найдено для ключа ${seatKey}`);
          }
        }

        seats.push(rowSeats);
      }
      
      // console.log('formatSeats по итогу seats ', seats);
      return seats;
    },
    getSeatKey(row, number) {
      if (!this.hallConfig || !this.hallConfig.seatsPerRow) {
        return 0;
      }
      // Возвращает ключ для доступа к объекту места
      // Добавляем логирование для отладки
      // console.log(`getSeatKey: row=${row}, number=${number}`);
      // console.log(`Вычисляем ключ: (${row-1}) * ${this.hallConfig.seatsPerRow} + (${number-1})`);
      
      // return (row - 1) * this.hallConfig.seatsPerRow + (number - 1); // начальный вариант
      return (row - 1) * this.hallConfig.seatsPerRow * 5 + (number - 1) * 5;
    },

    selectHall(hallId) {
      this.selectedHall = hallId;
      this.loadHallConfig(hallId);
      // this.loadHallConfig(hallId)
      //   .then(() => {
      //       this.updateSeatsLayout();
      //   })
      //   .catch(error => {
      //       console.error('Ошибка при выборе зала:', error);
      //   });
      // this.generateSeats();
      // this.updateSeatsLayout();
    },
    btnCanselPrise() {
      // Сброс значений
      this.selectedHall = null;
      this.prices = {
        standart: 0,
        vip: 0
      };
    },
    btnSavePrise() {
      if (!this.selectedHall) {
        alert('Выберите зал!');
        return;
      }

      if (this.prices.standart <= 0 || this.prices.vip <= 0) {
        alert('Цены должны быть больше нуля!');
        return;
      }

      axios.post('http://127.0.0.1:8000/halls/update-prices', {
        hall_id: this.selectedHall,
        prices: this.prices
      })
        .then(response => {
          alert('Цены успешно сохранены!');
        })
        .catch(error => {
          console.error(error);
          alert('Ошибка при сохранении цен');
        });
    },
    btnEditKino(movie) {
      console.log('Add Edit Kino', movie.title);
    },
    btnCanselKinoSession() {
      console.log('Cansel Kino Session');
    },
    btnSaveKinoSession() {
      console.log('Save Kino Session');
    },
    btnOpenShopKino() {
      console.log('Open shop Kino');
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
    // получение всех фильмов
    getMovies() {
      axios.get('http://127.0.0.1:8000/movies')
        .then(response => {
          this.movies = response.data;
          // console.log('movies: ', this.movies);
        })
        .catch(error => {
          console.error(error);
        });
    },
    // получение всех сеансов
    getSessions() {
      axios.get('http://127.0.0.1:8000/sessions')
        .then(response => {
          this.sessions = response.data;
          // console.log('sessions: ', this.sessions);
        })
        .catch(error => {
          console.error(error);
        });
    },
    timeToMinutes(t) {
      // t формат HH:mm
      const [h, m] = t.split(':').map(Number);
      return h * 60 + m;
    },
    minutesBetween(a, b) {
      // а и b в формате 'HH:mm'
      return this.timeToMinutes(b) - this.timeToMinutes(a);
    },
    sessionsByHall(hallId) {
      return this.sessions.filter(s => s.hall_id === hallId);
    },
    sessionsByMovie() {
      // console.log('editMovieID ' + this.editMovieID);
      return this.sessions.filter(s => s.movie_id === (this.editMovieID + 1));
    },
    // openSales(sessionId) {  
    //   console.log('Открыта продажа билетов');
    //   axios.post(`http://127.0.0.1:8000/sessions/${sessionId}/open`, {}, {  
    //     preserveScroll: true,  
    //     onSuccess: (resp) => {  
    //       // обновить локальные данные сессии  
    //       const updated = resp.props?.session;  
    //       // this.updateSessionInList(updated);  
    //     }  
    //   });  
    // },  
    // closeSales(sessionId) {
    //   console.log('Закрыта продажа билетов');  
    //   axios.post(`http://127.0.0.1:8000/sessions/${sessionId}/close`, {}, {  
    //     preserveScroll: true,  
    //     onSuccess: (resp) => {  
    //       const updated = resp.props?.session;  
    //       // this.updateSessionInList(updated);  
    //     }  
    //   });  
    // },
    // updateSessionInList(updatedSession) {
    //   if (!updatedSession) return;

    //   // Если есть локальный массив this.sessionsOpen
    //   const idx = this.sessionsOpen.findIndex(s => s.id === updatedSession.id);
    //   if (idx !== -1) {
    //     // заменить элемент
    //     this.$set(this.sessionsOpen, idx, updatedSession);
    //   } else {
    //     // если сессия еще не была в списке, можно добавить
    //     this.sessionsOpen.push(updatedSession);
    //   }
    // },
    async fetchStatus() {
      try {
        const res = await axios.get('http://127.0.0.1:8000/admin/sales/status');
        this.globalSalesOpen = !!res.data.data.sales_globally_open;
      } catch (e) {
        console.error('Не удалось получить статус глобальных продаж', e);
      }
    },
    async openAllSales() {
      try {
        await axios.post('http://127.0.0.1:8000/admin/sales/open-all');
        this.globalSalesOpen = true;
        console.log('this.globalSalesOpen ', this.globalSalesOpen);
      } catch (e) {
        console.error('Ошибка открытия глобальных продаж', e);
      }
    },
    async closeAllSales() {
      try {
        await axios.post('http://127.0.0.1:8000/admin/sales/close-all');
        this.globalSalesOpen = false;
        console.log('this.globalSalesOpen ', this.globalSalesOpen);
      } catch (e) {
        console.error('Ошибка закрытия глобальных продаж', e);
      }
    },
    // для попап
    handleScroll() {
      const scrollTop = window.scrollY;
      this.$refs.popup1.style.top = `${scrollTop + 10}px`;
      this.$refs.popup2.style.top = `${scrollTop + 10}px`;
      this.$refs.popup3.style.top = `${scrollTop + 10}px`;
      this.$refs.popup4.style.top = `${scrollTop + 10}px`;
    },

  },
  // watch: {
  //   'hallConfig.rows': {
  //     handler(newValue) {
  //       if (newValue > 0) {
  //         this.generateSeats();
  //       }
  //     },
  //     immediate: true
  //   },

  //   'hallConfig.seatsPerRow': {
  //     handler(newValue) {
  //       if (newValue > 0) {
  //         this.generateSeats();
  //       }
  //     },
  //     immediate: true
  //   }
  // },
  beforeDestroy() {
    window.removeEventListener('scroll', this.handleScroll);
  },
  mounted() {
    // fetch данных о зале 
    document.body.classList.add('page-admin');
    window.addEventListener('scroll', this.handleScroll); // для скролла попап

    this.getHalls();
    this.getMovies();
    this.getSessions();
    // this.fetchHalls();
    // this.generateSeats();
    this.updateSeatsLayout();

    // из файла js/accordeon.js
    const headers = Array.from(document.querySelectorAll('.conf-step__header'));
    headers.forEach(header => header.addEventListener('click', () => {
      header.classList.toggle('conf-step__header_closed');
      header.classList.toggle('conf-step__header_opened');
    }));
  }
}
</script>

<template>
  <header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторррская</span>
  </header>

  <router-link :to="{ name: 'Logout' }" class="link_exit">Exit</router-link>

  <main class="conf-steps">
    <section class="conf-step">
      <header class="conf-step__header conf-step__header_opened">
        <h2 class="conf-step__title">Управление залами</h2>
      </header>
      <div class="conf-step__wrapper">
        <p class="conf-step__paragraph">Доступные залы:</p>
        <ul class="conf-step__list">
          <div v-for="hall in halls" :key="hall" class="hall">
            <li>{{ hall?.name }}
              <button class="conf-step__button conf-step__button-trash" @click="btnHallDel(hall.id)"></button>
            </li>
          </div>
        </ul>
        <button class="conf-step__button conf-step__button-accent" @click="toglePopupHall">Создать зал</button>
      </div>
    </section>

    <section class="conf-step">
      <header class="conf-step__header conf-step__header_opened">
        <h2 class="conf-step__title">Конфигурация залов</h2>
      </header>
      <div class="conf-step__wrapper">
        <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
        <ul class="conf-step__selectors-box">
          <div v-for="hall in halls" :key="hall.id" class="hall">
            <li><input type="radio" class="conf-step__radio" name="chairs-hall" :value="hall.id"
                :checked="hall.id === selectedHall" @change="selectHall(hall.id)"><span class="conf-step__selector">Зал
                {{
                  hall?.name }}</span></li>
          </div>
        </ul>
        <p class="conf-step__paragraph">Укажите количество рядов и максимальное количество кресел в ряду:</p>
        <div class="conf-step__legend">
          <label class="conf-step__label">Рядов, шт<input type="number" class="conf-step__input"
              v-model.number="hallConfig.rows" @input="updateRows" placeholder="10"></label>
          <span class="multiplier">x</span>
          <label class="conf-step__label">Мест, шт<input type="number" class="conf-step__input"
              v-model.number="hallConfig.seatsPerRow" @input="updateSeatsPerRow" placeholder="8"></label>
        </div>
        <p class="conf-step__paragraph">Теперь вы можете указать типы кресел на схеме зала:</p>
        <div class="conf-step__legend">
          <span class="conf-step__chair conf-step__chair_standart" @click="selectSeatType('standart')"></span> — обычные
          кресла
          <span class="conf-step__chair conf-step__chair_vip" @click="selectSeatType('vip')"></span> — VIP кресла
          <span class="conf-step__chair conf-step__chair_disabled" @click="selectSeatType('disabled')"></span> —
          заблокированные (нет кресла)
          <p class="conf-step__hint">Чтобы изменить вид кресла, нажмите по нему левой кнопкой мыши</p>
        </div>

        <!-- отображаемый зал -->
        <div v-if="hallConfig.seats.length > 0" class="conf-step__hall">
          <div class="conf-step__hall-wrapper">
            <div v-for="(row, rowIndex) in hallConfig.seats" :key="rowIndex" class="conf-step__row">
              <span v-for="(seat, seatIndex) in row" :key="seatIndex"
                :class="`conf-step__chair conf-step__chair_${seat.type}`" @click="changeSeatType(seat)"></span>
            </div>
          </div>
        </div>

        <fieldset class="conf-step__buttons text-center">
          <button class="conf-step__button conf-step__button-regular" @click="btnCenselHallSeats">Отмена</button>
          <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent"
            @click="btnSaveHallSeats">
        </fieldset>
      </div>
    </section>

    <!-- конфигурация цен -->
    <section class="conf-step">
      <header class="conf-step__header conf-step__header_opened">
        <h2 class="conf-step__title">Конфигурация цен</h2>
      </header>
      <div class="conf-step__wrapper">
        <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
        <ul class="conf-step__selectors-box">
          <div v-for="hall in halls" :key="hall" class="hall">
            <li><input type="radio" class="conf-step__radio" name="prices-hall" :value="hall.id" v-model="selectedHall">
              <span class="conf-step__selector">Зал {{ hall?.name }}</span>
            </li>
          </div>
        </ul>

        <p class="conf-step__paragraph">Установите цены для типов кресел:</p>
        <div class="conf-step__legend">
          <label class="conf-step__label">Цена, рублей
            <input type="number" class="conf-step__input" placeholder="0" v-model.number="prices.standart" min="0"
              step="1" required>
          </label>
          за <span class="conf-step__chair conf-step__chair_standart"></span> обычные кресла
        </div>
        <div class="conf-step__legend">
          <label class="conf-step__label">Цена, рублей
            <input type="number" class="conf-step__input" placeholder="0" v-model.number="prices.vip">
          </label>
          за <span class="conf-step__chair conf-step__chair_vip"></span> VIP кресла
        </div>

        <fieldset class="conf-step__buttons text-center">
          <button class="conf-step__button conf-step__button-regular" @click="btnCanselPrise">Отмена</button>
          <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent"
            @click="btnSavePrise">
        </fieldset>
      </div>
    </section>

    <section class="conf-step">
      <header class="conf-step__header conf-step__header_opened">
        <h2 class="conf-step__title">Сетка сеансов</h2>
      </header>
      <div class="conf-step__wrapper">
        <p class="conf-step__paragraph">
          <button class="conf-step__button conf-step__button-accent" @click="toglePopupAddMovie">Добавить фильм</button>
        </p>
        <div class="conf-step__movies">
          <div v-for="movie in movies" :key="movie" class="conf-step__movie" @click="toglePopupEditMovie(movie?.id)">
            <img class="conf-step__movie-poster" alt="poster" :src=movie?.image_url>
            <h3 class="conf-step__movie-title">{{ movie?.title }}</h3>
            <p class="conf-step__movie-duration">{{ movie?.duration }} минут</p>
          </div>
        </div>

        <div class="conf-step__seances">
          <div v-for="hall in halls" :key="hall" class="hall">
            <div class="conf-step__seances-hall">
              <h3 class="conf-step__seances-title">Зал: {{ hall?.name }} (ID: {{ hall?.id }})</h3>
              <div class="conf-step__seances-timeline">
                <div v-for="session in sessionsByHall(hall.id)" :key="session" class="conf-step__seances-movie"
                  :style="{ left: computeLeft(session.start_time) + 'px' }">
                  <p class="conf-step__seances-movie-title">{{ movies[session.movie_id - 1]?.title }}</p>
                  <p class="conf-step__seances-movie-start">{{ session?.start_time }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <fieldset class="conf-step__buttons text-center">
          <button class="conf-step__button conf-step__button-regular" @click="btnCanselKinoSession">Отмена</button>
          <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent"
            @click="btnSaveKinoSession">
        </fieldset>
      </div>
    </section>

    <!-- вкл/выкл сессии продажи билетов -->
    <section class="conf-step">
      <header class="conf-step__header conf-step__header_opened">
        <h2 class="conf-step__title">Открыть продажи</h2>
      </header>
      <!-- <div class="conf-step__wrapper text-center">
        <p class="conf-step__paragraph">Всё готово, теперь можно:</p>
        <button class="conf-step__button conf-step__button-accent" @click="btnOpenShopKino">Открыть продажу
          билетов</button>
      </div> -->

      <!-- новая кнопка для открытия продаж -->
      <div class="conf-step__wrapper text-center">
        <section class="admin-settings conf-step__paragraph">
          <h2>Глобальные продажи</h2>
          <p>Статус: <strong>{{ globalSalesOpen ? 'Открыты' : 'Закрыты' }}</strong></p>
          <div class="admin-settings__controls">
            <button @click="openAllSales" :disabled="globalSalesOpen"
              class="conf-step__button conf-step__button-accent">
              Открыть продажи во всем приложении
            </button>
            <button @click="closeAllSales" :disabled="!globalSalesOpen"
              class="conf-step__button conf-step__button-accent">
              Закрыть продажи во всем приложении
            </button>
          </div>
        </section>
      </div>


    </section>
  </main>

  <!-- popup add Halls -->
  <div class="popup" ref="popup4" :class="{ 'popup__invisibl': popupHidden }">
    <!-- в форм: method="post" autocomplete="on" -->
    <form @submit.prevent="submitFormHalls" class="popup__form popup__container" method="post" autocomplete="on">
      <div class="popup__header">
        <h1 class="popup__title">Add Halls</h1>
      </div>
      <div class="popup__row">
        <div class="popup__container__poster">
          <img src="" alt="poster" class="popup__poster">
        </div>

        <div class="popup__container__cont">
          <label for="name" class="conf-step__paragraph">Name</label>
          <input type="text" class="c" placeholder="Big Hall" name="name" id="name" v-model="formHallData.name">
          <label for="rows" class="conf-step__paragraph">Rows</label>
          <input type="text" class="c" placeholder="10" name="rows" id="rows" v-model="formHallData.rows">
          <label for="seats_per_row" class="conf-step__paragraph">seats per row hall</label>
          <input type="text" class="c" placeholder="10" name="seats_per_row" id="seats_per_row"
            v-model="formHallData.seats_per_row">
          <label for="amountStandart" class="conf-step__paragraph">amount Standart seat in hall</label>
          <input type="text" class="c" placeholder="200" name="amountStandart" id="amountStandart"
            v-model="formHallData.amountStandart">
          <label for="vip" class="conf-step__paragraph">Amount Vip seat in hall</label>
          <input type="text" class="c" placeholder="500" name="amountVip" id="amountVip"
            v-model="formHallData.amountVip">
          <label for="active" class="conf-step__paragraph">active</label>
          <input type="radio" class="c" name="active" id="active" v-model="formHallData.active" checked>

          <button class="btnPopupHalls" type="submit" @click="toglePopupHall">Create Hall</button>
          <button class="btnPopupHalls" type="reset" @click="toglePopupHall">Censel</button>
        </div>
      </div>
    </form>
  </div>

  <!-- popup add movie -->
  <div class="popup" ref="popup3" :class="{ 'popup__invisiblAM': popupHiddenAM }">
    <!-- в форм: method="post" autocomplete="on" -->
    <form @submit.prevent="submitFormAddMovie" class="popup__form popup__container" method="post" autocomplete="on">
      <div class="popup__header">
        <h1 class="popup__title">Add Movie</h1>
      </div>
      <div class="popup__row">
        <div class="popup__container__poster">
          <img src="" alt="poster" class="popup__poster">
        </div>

        <div class="popup__container__cont">
          <label for="title" class="conf-step__paragraph">Name</label>
          <input type="text" class="c" placeholder="Big Kino" name="title" id="title" v-model="formMovieData.title">
          <label for="description" class="conf-step__paragraph">description</label>
          <input type="text" class="c" placeholder="description" name="description" id="description"
            v-model="formMovieData.description">
          <label for="duration" class="conf-step__paragraph">duration</label>
          <input type="text" class="c" placeholder="100" name="duration" id="duration" v-model="formMovieData.duration">
          <label for="country" class="conf-step__paragraph">country</label>
          <input type="text" class="c" placeholder="США" name="country" id="country" v-model="formMovieData.country">
          <label for="image_url" class="conf-step__paragraph">image_url</label>
          <input type="text" class="c" placeholder="image_url" name="image_url" id="image_url"
            v-model="formMovieData.image_url">

          <button class="btnPopupHalls" type="submit" @click="toglePopupAddMovie">Create Movie</button>
          <button class="btnPopupHalls" type="reset" @click="toglePopupAddMovie">Censel</button>
        </div>
      </div>
    </form>
  </div>


  <!-- popup edit movie -->
  <div class="popup" ref="popup1" :class="{ 'popup__invisiblEM': popupHiddenEM }">
    <!-- в форм: method="post" autocomplete="on" -->
    <form @submit.prevent="submitFormEditMovie" class="popup__form popup__container" method="post" autocomplete="on">
      <div class="popup__header">
        <h1 class="popup__title">Edit Movie</h1>
      </div>
      <div class="popup__row">
        <div class="popup__container__poster">
          <img :src=movies[editMovieID]?.image_url alt="poster" class="popup__poster">
        </div>

        <div class="popup__container__cont">
          <span class="conf-step__paragraph">Name: {{ movies[editMovieID]?.title }} </span>
          <input type="text" class="c" placeholder="Big Kino" name="title" id="title" v-model="formMovieData.title">
          <label for="description" class="conf-step__paragraph">description: {{ movies[editMovieID]?.description
            }}</label>
          <input type="text" class="c" placeholder="description" name="description" id="description"
            v-model="formMovieData.description">
          <label for="duration" class="conf-step__paragraph">duration: {{ movies[editMovieID]?.duration }}</label>
          <input type="text" class="c" placeholder="100" name="duration" id="duration" v-model="formMovieData.duration">
          <label for="country" class="conf-step__paragraph">country: {{ movies[editMovieID]?.country }}</label>
          <input type="text" class="c" placeholder="США" name="country" id="country" v-model="formMovieData.country">
          <label for="image_url" class="conf-step__paragraph">image_url: {{ movies[editMovieID]?.image_url }}</label>
          <input type="text" class="c" placeholder="image_url" name="image_url" id="image_url"
            v-model="formMovieData.image_url">


          <h3>Sessions</h3>
          <ul class="conf-step__selectors-box">
            <!-- <div v-for="session in sessionsByMovie()" :key="session" class="session">  -->
            <li v-for="session in sessionsByMovie()" :key="session" class="session">
              <span class="conf-step__selector">Session ID {{ session?.id }}, Hall ID: {{ session?.hall_id }}, Время
                сеанса {{ session?.start_time }}</span>
              <button class="conf-step__button conf-step__button-trash" @click="btnSessionDel(session?.id)"></button>
            </li>
            <!-- </div>  -->
          </ul>

          <button class="btnPopupHalls" @click="toglePopupAddSessionMovie">Add session movie</button>
          <button class="btnPopupHalls" type="submit" @click="toglePopupEditMovieClose">Update Movie</button>
          <button class="btnPopupHalls" @click="btnMovieDel">Удалить кино</button>
          <button class="btnPopupHalls" type="reset" @click="toglePopupEditMovieClose">Censel</button>
        </div>
      </div>
    </form>
  </div>

  <!-- popup add sessios movie -->
  <div class="popup popup__plus" ref="popup2" :class="{ 'popup__invisiblAddSessionMovie': popupHiddenAddSessionMovie }">
    <form @submit.prevent="submitFormAddSessionMovie" class="popup__form popup__container" method="post"
      autocomplete="on">
      <div class="popup__header">
        <h1 class="popup__title">Edit Session Movie</h1>
        <h1 class="popup__title">{{ movies[editMovieID]?.title }}</h1>
      </div>
      <div class="popup__row">
        <div class="popup__container__cont">
          <span class="conf-step__paragraph">Movie id: {{ editMovieID }} - {{ movies[editMovieID]?.title }}</span>
          <label for="hall_id" class="conf-step__paragraph">hall_id</label>
          <input type="number" class="c" placeholder="1" name="hall_id" id="hall_id"
            v-model.number="formMovieSessionData.hall_id">
          <label for="start_time" class="conf-step__paragraph">start_time</label>
          <input type="start_time" class="c" placeholder="19:50" name="start_time" id="start_time"
            v-model="formMovieSessionData.start_time">

          <h3>Sessions</h3>
          <ul class="conf-step__selectors-box">
            <div v-for="session in sessionsByMovie()" :key="session?.id" class="session">
              <li>
                <span class="conf-step__selector">Session ID {{ session?.id }}, Hall ID: {{ session?.hall_id }}, Время
                  сеанса {{ session?.start_time }}</span>
                <button class="conf-step__button conf-step__button-trash" @click="btnSessionDel(session?.id)"></button>
              </li>
            </div>
          </ul>

          <button class="btnPopupHalls" type="submit" @click="toglePopupAddSessionMovie">Add session movie</button>
          <button class="btnPopupHalls" type="reset" @click="toglePopupAddSessionMovie">Censel</button>
        </div>
      </div>
    </form>
  </div>
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
  width: 60px;
  background-color: rgb(133, 255, 137);
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
  /* display: none; */
  /* position: fixed; */
  position: absolute;
  width: 80vw;
  height: 90vh;
  z-index: 100;
  left: 50px;
  top: 10px;
  overflow-y: auto;
  background: rgba(220, 208, 226, 0.9);
}

.popup__plus {
  position: absolute;
  width: 80vw;
  height: 90vh;
  z-index: 110;
  left: 50px;
  top: 50px;
  overflow-y: auto;
  background: rgba(248, 255, 123, 0.9);
}

.popup__invisibl {
  display: none;
}

.popup__invisiblAM {
  display: none;
}

.popup__invisiblEM {
  display: none;
}

.popup__invisiblAddSessionMovie {
  display: none;
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
  flex-wrap: wrap;
  flex-direction: column;
  justify-content: start;
}

.popup__row {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  margin-top: 20px;
}

.popup__container__poster {
  width: 30%;
  margin-left: 20px;
}

.popup__container__cont {
  width: 65%;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  margin-right: 20px;
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

.link_exit {

  box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.24), 0px 0px 2px rgba(0, 0, 0, 0.12);
  border-radius: 2px;
  background-color: #FFFFFF;
  color: #000000;
  text-decoration: none;
  font-size: 1.3rem;
  padding: 5px;
}

.link_exit:hover,
.link_exit:active {
  background-color: #000000;
  color: #FFFFFF;
}

.admin-settings {
  padding: 16px;
  border: 1px solid #ddd;
  border-radius: 8px;
}

.admin-settings__controls {
  display: flex;
  gap: 12px;
  margin-top: 8px;
}

button[disabled] {
  opacity: 0.5;
  cursor: not-allowed;
}
</style>