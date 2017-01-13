<template>
  <div id="app">  
    <div class="controll">
      <div>
        <label for="">キーワード：</label> <input type="text" v-model="keyword">
        <button @click="searchEvent">検索</button>
      </div>
      <button @click="downloadFile">
        jsonダウンロード
      </button>
    </div>
    <div class="select">
      <div>
        <h2>検索結果</h2>
        <ul class="event-list">
          <event v-for="event in events" :event="event" >
            <my-button :onClick="clickAdd.bind(null, event)">
              追加
            </my-button>
          </event>
        </ul>
      </div>
      <div>
        <h2>選択中のイベント</h2>
        <ul class="event-list">
          <event v-for="event in selected" :event="event" >
            <my-button :onClick="clickRemove.bind(null, event)">
              削除
            </my-button>
          </event>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import request from 'superagent'
import download from 'downloadjs'
import moment from 'moment'

import Event from './component/event.vue'
import MyButton from './component/button.vue'

export default {
  name: 'app',
  data () {
    return {
      events: [],
      selected: [],
      keyword: '',
    }
  },
  methods: {
    clickAdd(event) {
      this.addSelected(event)
    },
    clickRemove(event) {
      this.removeSelected(event)
    },
    addSelected(event) {
      if(!this.selected.includes(event)) this.selected.push(event)
    },
    removeSelected(event) {
      if(this.selected.includes(event)) {
        let index = this.selected.indexOf(event)
        this.selected.splice(index, 1)
      }      
    },
    downloadFile() {
      let data = JSON.stringify(this.filterSelected, null, 2)
      download(data, `mock-${moment().format('YYYY-MM-DD-h-mm-ss')}.json`, 'text/json')
    },
    searchEvent() {
      request.get('http://localhost:8000/src/api/connpass.php')
      .query({
        keyword: this.keyword,      
         count: 100,      
      })      
      .end((err, res) => {
        this.events = res.body.events
      })
    }
  },
  computed: {
    filterSelected() {
      return this.selected.map((event) => {
        return {
          "code": getCode(),
          "name": event.title,
          "field": "分野名(it, game, design, movie, other)",
          "discription": event.description,
          "opening_date": moment(event.started_at).format('YYYY-MM-DD'),
          "start_at": moment(event.started_at).format('HH:mm:ss'),
          "end_at": moment(event.ended_at).format('HH:mm:ss'),
          "place" : getPlace(),
          "capacity": event.limit,
          "open_date": moment(event.started_at).subtract(1, 'month').format('YYYY-MM-DD'),
          "organaizer_code": "主催者のコード(user.jsonの中から選んで)",
          "recruit_start_date": moment(event.started_at).subtract(1, 'month').format('YYYY-MM-DD'),
          "recruit_end_date": moment(event.started_at).subtract(1, 'day').format('YYYY-MM-DD'),
          "recruit_start_at": "10:00:00",
          "recruit_end_at": "24:00:00"
        }
      })
    }
  },
  created () {
    request.get('http://localhost:8000/src/api/connpass.php')
      .query({})
      .end((err, res) => {
        this.events = res.body.events
      })
  },
  components: {
    Event,
    MyButton
  }
}

let codes = []

let getCode = () => {
  let code
  do{
    code = Math.random().toString(36).slice(-7)
  }while(codes.indexOf(code) !== -1)
  return code
} 

let getPlace = () => {
  let rooms = [
    '2A','2B',					
    '3A','3B','3C','3D',
    '4A','4B','4C','4D',
    '5A','5B','5C','5D',
    '6A','6B','6C','6D',
    '7A','7B','7C','7D',
    '8A','8B','8C','8D',
    '9D-1','9D-2'					
  ]
  return rooms[Math.floor(Math.random() * rooms.length)]
}

</script>

<style>
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;  
  color: #2c3e50;
  margin-top: 60px;
}

h1, h2 {
  font-weight: normal;
}

ul {
  list-style-type: none;
  padding: 0;
}

li {
  display: inline-block;
  margin: 0 10px;
}

a {
  color: #42b983;
}

.select {
  display: flex
}

.event-list {
  width: 500px;
  height: 80vh;
  overflow: scroll;
}
</style>
