<template>
  <div>
    <h2>Players</h2>
    <form @submit.prevent="addPlayer" class="mb-3">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Player's Name.." v-model="player.name" />
        <br />
        <select v-model="player.game_id" class="form-control">
          <option value selected disabled>Select Game..</option>
          <option v-for="game in games" v-bind:value="game.id" v-bind:key="game.id">{{ game.name }}</option>
        </select>
        <br />
        <input
          type="number"
          :readonly="edit"
          class="form-control"
          placeholder="Player's Score.."
          v-model="player.score"
        />
      </div>
      <button type="submit" class="btn btn-primary btn-block">Save</button>
    </form>
    <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
    <hr />
    <hr />
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchPlayers(pagination.prev_page_url)">Previous</a>
        </li>

        <li class="page-item disabled">
          <a
            class="page-link text-dark"
            href="#"
          >Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
        </li>

        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchPlayers(pagination.next_page_url)">Next</a>
        </li>
      </ul>
    </nav>
    <hr />
    <div class="card card-body mb-2">
      <div class="responsive-table">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Player's Name</th>
              <th>Game</th>
              <th>Score</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(player, index) in players" v-bind:key="player.id">
              <td>{{ index + 1 }}</td>
              <td>{{ player.name }}</td>
              <td>{{ player.game }}</td>
              <td>{{ player.score }}</td>
              <td>
                <button @click="editPlayer(player)" class="btn btn-warning">Edit</button>
                <button @click="deletePlayer(player.id)" class="btn btn-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="card card-body mb-2">
      <button @click="purgeDeletedPlayers()" class="btn btn-danger">Permanently Delete?</button>
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Deleted Players</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(player, index) in deletedPlayers" v-bind:key="player.id">
              <td>{{ index + 1 }}</td>
              <td>{{ player.name }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      players: [],
      deletedPlayers: [],
      player: {
        id: "",
        name: "",
        game_id: "",
        score: ""
      },
      player_id: "",
      pagination: {},
      games: [],
      edit: false
    };
  },

  created() {
    this.fetchPlayers();
    this.fetchGames();
    this.fetchDeletedPlayers();
  },

  methods: {
    fetchPlayers(page_url) {
      let token = localStorage.getItem("access_token");
      page_url = page_url || "/api/players";
      fetch(page_url, {
        method: "get",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.players = res.data;
          this.makePagination(res);
        })
        .catch(err => console.log(err));
    },
    fetchGames() {
      let token = localStorage.getItem("access_token");
      fetch("/api/games/all", {
        method: "get",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.games = res;
        })
        .catch(err => console.log(err));
    },
    fetchDeletedPlayers() {
      let token = localStorage.getItem("access_token");
      fetch("/api/players/deleted", {
        method: "get",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.deletedPlayers = res;
        })
        .catch(err => console.log(err));
    },
    makePagination(meta) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: meta.next_page_url,
        prev_page_url: meta.prev_page_url
      };
      this.pagination = pagination;
    },
    deletePlayer(id) {
      let token = localStorage.getItem("access_token");
      if (confirm("Are You Sure?")) {
        fetch(`api/players/${id}`, {
          method: "delete",
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            alert("Player Removed");
            this.fetchPlayers();
            this.fetchDeletedPlayers();
          })
          .catch(err => console.log(err));
      }
    },
    purgeDeletedPlayers() {
      let token = localStorage.getItem("access_token");
      if (confirm("Delete permanently?")) {
        fetch(`api/players/purge-deleted`, {
          method: "delete",
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            alert("Games destroyed from database.");
            this.fetchPlayers();
            this.fetchDeletedPlayers();
          })
          .catch(err => console.log(err));
      }
    },
    addPlayer() {
      let token = localStorage.getItem("access_token");
      if (this.edit === false) {
        // Add
        fetch("api/players", {
          method: "post",
          body: JSON.stringify(this.player),
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert("Player Added");
            this.fetchPlayers();
          })
          .catch(err => console.log(err));
      } else {
        let id = this.player.id;
        // Update
        fetch(`api/players/${id}`, {
          method: "post",
          body: JSON.stringify(this.player),
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert("Player Updated");
            this.fetchPlayers();
          })
          .catch(err => console.log(err));
      }
    },
    editPlayer(player) {
      this.edit = true;
      this.player.id = player.id;
      this.player.name = player.name;
      this.player.game_id = player.game_id;
      this.player.score = player.score;
    },
    clearForm() {
      this.edit = false;
      this.player.id = "";
      this.player.name = "";
      this.player.game_id = "";
      this.player.score = "";
    }
  }
};
</script>