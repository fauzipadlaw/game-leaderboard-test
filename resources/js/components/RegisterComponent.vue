<template>
  <div>
    <h2>Register User</h2>
    <form @submit.prevent="register" class="mb-3">
      <div class="form-group">
        <input type="text" class="form-control" placeholder="Name" v-model="user.name" required />
        <br />
        <input type="email" class="form-control" placeholder="Email" v-model="user.email" required />
        <br />
        <input
          type="password"
          class="form-control"
          placeholder="Password"
          v-model="user.password"
          required
        />
        <br />
        <input
          type="password"
          class="form-control"
          placeholder="Confirm Password"
          v-model="user.password_confirmation"
          required
        />
        <br />
      </div>
      <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      }
    };
  },
  methods: {
    register() {
      fetch("api/auth/register", {
        method: "post",
        body: JSON.stringify(this.user),
        headers: {
          "content-type": "application/json"
        }
      })
        .then(res => res.json())
        .then(data => {
          localStorage.setItem("access_token", data.token);
          this.$router.push("/");
        })
        .catch(err => console.log(err));
    }
  }
};
</script>