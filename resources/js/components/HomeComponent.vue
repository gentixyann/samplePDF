<template>
  <v-content>
    <v-container fluid fill-height>
      <v-layout justify-center fluid>
        <v-flex xs12 offset-mx5>
          <v-card xs12 class="m-3 px-3">
             <v-card-title class="title">ダウンロード</v-card-title>
             <div class="text-center">
              <v-btn flat small fab>
                <a href="/pdf" title="">
                  ダウンロード
                </a>
            <v-icon color="success">create</v-icon>
          </v-btn>
             </div>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>
  export default {
    data: () => ({
    }),

    props: {
    },

    created() {
      console.log('Home Component created.')
      this.initialize()
    },

    mounted() {
      console.log('Home Component mounted.')
    },

    methods: {
      initialize: function() {
      },
    },

  }
</script>

