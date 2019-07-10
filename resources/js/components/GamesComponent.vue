<template>
  <div>
    <h2>Games</h2>
    <form @submit.prevent="addGame" class="mb-3">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Game's Name.." v-model="game.name" />
      </div>
      <button type="submit" class="btn btn-primary btn-block">Save</button>
    </form>
    <button @click="clearForm()" class="btn btn-danger btn-block">Cancel</button>
    <hr />
    <hr />
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchGames(pagination.prev_page_url)">Previous</a>
        </li>

        <li class="page-item disabled">
          <a
            class="page-link text-dark"
            href="#"
          >Page {{ pagination.current_page }} of {{ pagination.last_page }}</a>
        </li>

        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
          <a class="page-link" href="#" @click="fetchGames(pagination.next_page_url)">Next</a>
        </li>
      </ul>
    </nav>
    <hr />
    <div class="card card-body mb-2">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Game's Name</th>
            <th>Players</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(game, index) in games" v-bind:key="game.id">
            <td>{{ index + 1 }}</td>
            <td>{{ game.name }}</td>
            <td>{{ game.total_players }}</td>
            <td>
              <button @click="editGame(game)" class="btn btn-warning">Edit</button>
              <button @click="deleteGame(game.id)" class="btn btn-danger">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      games: [],
      game: {
        id: "",
        name: ""
      },
      game_id: "",
      pagination: {},
      edit: false
    };
  },

  created() {
    this.fetchGames();
  },

  methods: {
    fetchGames(page_url) {
      let token = localStorage.getItem("access_token");
      page_url = page_url || "/api/games";
      fetch(page_url, {
        method: "get",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.games = res.data;
          this.makePagination(res);
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
    deleteGame(id) {
      let token = localStorage.getItem("access_token");
      if (confirm("Are You Sure?")) {
        fetch(`api/games/${id}`, {
          method: "delete",
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            alert("Game Removed");
            this.fetchGames();
          })
          .catch(err => console.log(err));
      }
    },
    addGame() {
      let token = localStorage.getItem("access_token");
      if (this.edit === false) {
        // Add
        fetch("api/games", {
          method: "post",
          body: JSON.stringify(this.game),
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert("Game Added");
            this.fetchGames();
          })
          .catch(err => console.log(err));
      } else {
        let id = this.game.id;
        // Update
        fetch(`api/games/${id}`, {
          method: "post",
          body: JSON.stringify(this.game),
          headers: {
            "content-type": "application/json",
            Authorization: `Bearer ${token}`
          }
        })
          .then(res => res.json())
          .then(data => {
            this.clearForm();
            alert("Game Updated");
            this.fetchGames();
          })
          .catch(err => console.log(err));
      }
    },
    editGame(game) {
      this.edit = true;
      this.game.id = game.id;
      this.game.name = game.name;
    },
    clearForm() {
      this.edit = false;
      this.game.id = "";
      this.game.name = "";
    }
  }
};
</script>