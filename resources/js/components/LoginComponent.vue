<template>
  <div>
    <h2>Login Page</h2>
    <form @submit.prevent="login" class="mb-3">
      <div class="form-group">
        <input type="email" class="form-control" placeholder="Email" v-model="creds.email" required />
        <br />
        <input
          type="password"
          class="form-control"
          placeholder="Password"
          v-model="creds.password"
          required
        />
      </div>
      <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      creds: {
        email: "",
        password: ""
      }
    };
  },
  methods: {
    login() {
      if (this.creds.email && this.creds.password) {
        fetch("api/auth/login", {
          method: "post",
          body: JSON.stringify(this.creds),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            localStorage.setItem("access_token", data.access_token);
            this.$router.push("/");
          })
          .catch(err => console.log(err));
      }
    }
  }
};
</script>