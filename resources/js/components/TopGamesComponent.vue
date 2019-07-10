<template>
  <div>
    <h2>Top Games</h2>
    <div class="card card-body mb-2">
      <div class="responsive-table">
        <table class="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Game's Name</th>
              <th>Total Players</th>
              <th>Top Player</th>
              <th>Score</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(game, index) in games" v-bind:key="game.id">
              <td>{{ index + 1 }}</td>
              <td>{{ game.name }}</td>
              <td>{{ game.total_players }}</td>
              <td>{{ game.top_player ? game.top_player.name : "-" }}</td>
              <td>{{ game.top_player ? game.top_player.score : "-" }}</td>
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
      games: []
    };
  },

  created() {
    this.fetchGames();
  },

  methods: {
    fetchGames() {
      let token = localStorage.getItem("access_token");
      fetch("/api/games/sort", {
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
    }
  }
};
</script>