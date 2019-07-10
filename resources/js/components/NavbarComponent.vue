<template>
  <nav class="navbar navbar-expand-sm navbar-dark bg-primary mb-2">
    <div class="container">
      <a href="/" class="navbar-brand">Game Leaderboard Test</a>
      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class="nav-item">
            <router-link class="nav-link" to="/games">Games</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/players">Players</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/token">Token</router-link>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/login">Login</router-link>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" @click.prevent="logout">Logout</a>
          </li>
          <li class="nav-item">
            <router-link class="nav-link" to="/register">Register</router-link>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  methods: {
    logout() {
      let token = localStorage.getItem("access_token");
      fetch("api/auth/logout", {
        method: "post",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          localStorage.removeItem("access_token");
          this.$router.push("/login");
        })
        .catch(err => {
          localStorage.removeItem("access_token");
          console.log(err);
          this.$router.push("/login");
        });
    }
  }
};
</script>