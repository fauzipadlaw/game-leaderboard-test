<template>
  <div>
    <h2>Informasi User</h2>
    <div class="card card-body mb-2">
      <table class="table">
        <tr>
          <td>Name</td>
          <td>{{ user.name }}</td>
        </tr>
        <tr>
          <td>Email</td>
          <td>{{ user.email }}</td>
        </tr>
      </table>
    </div>
    <hr />
    <hr />
    <h2>Informasi Token</h2>
    <div class="card card-body mb-2">
      <table class="table">
        <tr>
          <td>iss</td>
          <td>{{ token.iss }}</td>
        </tr>
        <tr>
          <td>iat</td>
          <td>{{ token.iat }}</td>
        </tr>
        <tr>
          <td>exp</td>
          <td>{{ token.exp }}</td>
        </tr>
        <tr>
          <td>nbf</td>
          <td>{{ token.nbf }}</td>
        </tr>
        <tr>
          <td>jti</td>
          <td>{{ token.jti }}</td>
        </tr>
        <tr>
          <td>sub</td>
          <td>{{ token.sub }}</td>
        </tr>
        <tr>
          <td>prv</td>
          <td>{{ token.prv }}</td>
        </tr>
      </table>

      <hr />
      <button class="btn btn primary" @click="refresh">Refresh Token</button>
      <div v-if="newToken">
        <code>{{ newToken }}</code>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        name: "",
        email: ""
      },
      token: {
        iss: "",
        iat: "",
        exp: "",
        nbf: "",
        jti: "",
        sub: "",
        prv: ""
      },
      newToken: ""
    };
  },
  created() {
    this.fetchUser();
    this.fetchTokenPayload();
  },
  methods: {
    fetchUser() {
      let token = localStorage.getItem("access_token");
      fetch("/api/auth/me", {
        method: "post",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.user = res;
        })
        .catch(err => console.log(err));
    },
    fetchTokenPayload() {
      let token = localStorage.getItem("access_token");
      fetch("/api/auth/payload", {
        method: "post",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.token = res;
        })
        .catch(err => console.log(err));
    },
    refresh() {
      let token = localStorage.getItem("access_token");
      fetch("/api/auth/refresh", {
        method: "post",
        headers: {
          "content-type": "application/json",
          Authorization: `Bearer ${token}`
        }
      })
        .then(res => res.json())
        .then(res => {
          this.newToken = res.access_token;
          localStorage.setItem("access_token", res.access_token);
          this.fetchTokenPayload();
        })
        .catch(err => console.log(err));
    }
  }
};
</script>