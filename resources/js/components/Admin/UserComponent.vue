<template>
  <v-flex>
    <v-card xs12 class="m-3 px-3">

      <v-card-title class="title">
        <v-icon class="pr-2">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }}

        <!-- ダイアログコンポーネントを呼び出し -->
        <user-dialog ref="userDialog" @click="open" @reload="reload" @setsearch="setsearch"></user-dialog>

        <v-spacer></v-spacer>
        <v-spacer></v-spacer>
        <v-text-field
          v-model="search"
          prepend-icon="search"
          label="Search"
          single-line
          hide-details
          clearable
        ></v-text-field>
      </v-card-title>

      <v-data-table
        :headers="headers"
        :items="tabledata"
        :pagination.sync="pagination"
        :rows-per-page-items='[10,25,50,{"text":"All","value":-1}]'
        :loading="loading"
        :search="search"
        class="elevation-0 p-1"
      >
        <v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>

        <template slot="items" slot-scope="props">
          <tr>
            <td class="text-xs-center" xs1>{{ (props.index + 1) + (pagination.page - 1) * pagination.rowsPerPage }}</td>
            <template v-for="n in (headers.length - 2)">
              <td :class="'text-xs-' + headers[n].align" style="white-space: nowrap;" v-text="props.item[headers[n].value]"></td>
            </template>
            <td class="text-xs-center" xs1>

              <!-- 一覧のデータに「更新」ボタンを追加 -->
              <v-btn flat small fab @click="dialogOpen(props.item)"><v-icon color="success">edit</v-icon></v-btn>

              <!-- 一覧のデータに「削除」ボタンを追加（ゴミ箱アイコン） -->
              <v-btn flat small fab @click="dialogOpen(props.item,true)"><v-icon color="error">delete</v-icon></v-btn>

            </td>
          </tr>
        </template>

      </v-data-table>
    </v-card>
  </v-flex>
</template>

<script>
  // ダイアログコンポーネント設定
  import user_dialog from './UserDialog.vue'
  export default {
    name: 'UserComponent',
    components: {
      'user-dialog': user_dialog,
    },
    props: {
    },
    data: () => ({
      loading: false,
      search: '',
      pagination: { sortBy: 'name', descending: false, },
      tabledata: [],
      headers: [
        { align: 'center', sortable: false, text: 'No',       },
        { align: 'left',   sortable: true,  text: '社員ID',   value: 'loginid' },
        { align: 'left',   sortable: true,  text: '氏名',     value: 'name' },
        { align: 'center', sortable: true,  text: '権限',     value: 'role' },
        // 一覧に「編集」、「削除」ボタン欄を追加
        { align: 'center', sortable: false, text: 'アクション',       },
      ],
    }),
    created() {
      if (process.env.MIX_DEBUG) console.log('User Component created.')
      this.initialize()
    },
    methods: {
      initialize() {
        this.getUsers()
      },
      // ダイアログの処理が終わったらリロードする用（子コンポーネントから実行）
      reload() {
        if (process.env.MIX_DEBUG) console.log('User Component reload')
        this.getUsers()
      },
      // ログインIDが被った時に一覧から絞り込み表示する用（子コンポーネントから実行）
      setsearch(id) {
        if (process.env.MIX_DEBUG) console.log('User Component set Search')
        this.search = id
      },
      getUsers() {
        if (process.env.MIX_DEBUG) console.log('User Component getUsers')
        this.loading = true
        axios.post('/api/admin/user')
        .then( function (response) {
          this.loading = false
          if (process.env.MIX_DEBUG) console.log(response)
          if (response.data.users) {
            this.tabledata = response.data.users
            this.setRole()
          }
        }.bind(this))
        .catch(function (error) {
          this.loading = false
          console.log(error)
          if (error.response && [401, 419].includes(error.response.status)) {
            this.$emit('axios-logout')
          }
        }.bind(this))
      },
      setRole() {
        for (var i=0; i<this.tabledata.length; i++) {
          if (this.tabledata[i].role) {
            if (this.tabledata[i].role == 5) { this.tabledata[i].role = '管理者'  }
            if (this.tabledata[i].role == 10) { this.tabledata[i].role = 'ユーザ'  }
          }
        }
      },
      // 一覧のデータを設定してダイアログを呼び出す（削除の場合は第二引数に true を設定）
      dialogOpen(item,flg) {
        if (process.env.MIX_DEBUG) console.log('User Component dialog open')
        this.$refs.userDialog.open(item, (flg || false))
      },
      open() {
        console.log('母は');
      }
    },
  }
</script>