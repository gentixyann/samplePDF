<template>
  <transition name="fade">
  <v-dialog v-model="dialog" max-width="650px" persistent>

     <!-- ユーザ一覧に表示する「新規追加」ボタンを設定 -->
    <v-btn slot="activator" color="primary" dark class="mb-2" flat @click="open(null)">
      <v-icon class="pr-2">person_add</v-icon>
    </v-btn>
    <v-card>

      <!-- モードによってタイトルを変える（新規、編集、削除） -->
      <v-toolbar :color="titlecolor" dark>
        <v-toolbar-title><v-icon class="pb-1">{{ $route.meta.icon }}</v-icon> {{ $route.meta.name }} | {{ title }}</v-toolbar-title>
      </v-toolbar>

      <v-card-text>
        <v-container>
          <v-layout column wrap>

            <!-- 名前欄：必須項目、最低２文字、最大６４文字、「削除」時は無効化 -->
            <v-text-field class="pb-3" label="名前" placeholder="氏名を入力してください"
                          v-model="items.name"    
                          :error-messages="error.name"
                          :rules="[rules.required, rules.min2]"
                          maxlength="64"
                          required
                          counter
                          :disabled="type == 'D'"
            ></v-text-field>

            <!-- ログインID欄：必須項目、最低６文字、最大１２８文字、「新規」時以外は無効化（ログインIDは更新できない） -->
            <v-text-field class="pb-3" label="ログインID" placeholder="社員ＩＤを入力してください"
                          v-model="items.loginid" 
                          :error-messages="error.loginid" 
                          :rules="[rules.required, rules.min6]"
                          maxlength="128"
                          required
                          counter
                          :disabled="type != 'C'"
            ></v-text-field>

            <!-- パスワード欄：任意項目、最大１２８文字、「削除」時は無効化 -->
            <v-text-field class="pb-2" label="パスワード" :placeholder="placeholder_password"
                          v-model="items.pass"    
                          :error-messages="error.pass"
                          maxlength="128"
                          counter
                          :disabled="type == 'D'"
            ></v-text-field>

            <!-- 権限欄：管理者権限をつける場合にチェック、「削除」時は無効化 -->
            <v-checkbox v-model="items.role"    label="管理者権限" :disabled="type == 'D'"></v-checkbox>
          </v-layout>
        </v-container>
      </v-card-text>
      
      <v-card-actions>

        <!-- キャンセルボタン：　押すとダイアログを閉じる -->
        <v-btn color="gray    darken-1" flat block @click.native="close">キャンセル</v-btn>

        <!-- 保存ボタン：押すと保存処理(save)を実行、「削除」時は表示しない -->
        <v-btn color="primary darken-1"  flat block @click.native="save" v-show="type != 'D'">保存</v-btn>

        <!-- 削除ボタン：押すと削除処理(destroy)を実行、「削除」時のみ表示 -->
        <v-btn color="error   darken-1"  flat block @click.native="destroy" v-show="type == 'D'">削除</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
  </transition>
</template>

<script>
  export default {
    name: 'UserDialog',
    props: {
    },
    data: () => ({
       //最初はダイアログは閉じた状態
      dialog: false,
      title: '編集',
      titlecolor: 'primary',
      placeholder_password: '',
      type: '',

      // 対象データを保持する領域（loginid、name、pass、role の各項目）
      items: { 
        loginid: '',
        name: '',
        pass: '',
        role: false,
      },

      // 変更があったかを確認するための変更前データを保持する
      orig: {},

      // サーバ通信後にエラーがあった場合のエラーメッセージを保持する
      error: {},

      // 入力ルールを設定（最低文字とか）
      rules: {
        required: value => !!value || 'Required.',
        min2: value => value.length >= 2 || 'Min 2 characters',
        min6: value => value.length >= 6 || 'Min 6 characters',
      },
    }),
    created() {
      if (process.env.MIX_DEBUG) console.log('User Dialog created.')
    },
    methods: {
      // 変数領域を初期化する
      clearVar() {
        this.dialog = true
        this.clearError()
        this.items = JSON.parse(JSON.stringify(this.error))
        this.orig = JSON.parse(JSON.stringify(this.error))
      },
      // 変数領域を初期化する
      clearError() {
        this.error = { 
          loginid: '', 
          name: '', 
          pass: '', 
          role: false, 
        }
      },
      // ダイアログを閉じる
      close() {
        if (process.env.MIX_DEBUG) console.log("User Dialog func close")
        this.dialog = false
      },
      // 保存判定部分、変更前データと比較して変わってる部分があるかをチェックしている
      save() {
        if (process.env.MIX_DEBUG) console.log("User Dialog func save")
        // 変更があった時だけ通信
        if (JSON.stringify(this.orig).replace(/[\s|　]+/g,'') !== JSON.stringify(this.items).replace(/[\s|　]+/g,'')){
          this.store()
        } 
        // 変更がなければただ閉じる
        else {
          this.close()
        }
      },

      // ダイアログを表示する部分
      // 編集や削除の時は対象データ(item)を受け取る
      // 削除の時はフラグ(flg)を受け取る
      open(item, flg) {
        if (process.env.MIX_DEBUG) 
          console.log("User Dialog func open")
        // INIT VAR
        this.clearVar()
        // SET TYPE
        if (flg) this.type = 'D' // DELETE
        else if (item) this.type = 'U' // UPDATE
        else this.type = 'C' // CREATE
        // USER CREATE
        if (this.type == 'C') {
          this.title = "新規追加"
          this.titlecolor = 'primary',
          this.placeholder_password = "パスワードを指定してください（未指定の場合はログインＩＤを設定）"
        }
        // USER UPDATE
        if (this.type == 'U') {
          this.title = "編集"
          this.titlecolor = 'accent',
          this.placeholder_password = "変更する場合はパスワードを指定してください（未指定の場合は変更しない）"
        }
        // USER DELETE
        if (this.type == 'D') {
          this.title = "削除"
          this.titlecolor = 'error',
          this.placeholder_password = ""
        }
          // SET ITEM
        if (item) {
          if (item.loginid) this.items.loginid = item.loginid
          if (item.name) this.items.name = item.name
          if (item.role) {
            if (item.role == '管理者') {
              this.items.role = true
            }
          }
          // 変更箇所を把握するために開いた直後の状態をコピーして保持しとく
          // COPY ORIG
          this.orig = JSON.parse(JSON.stringify(this.items))
        }
      },

      //保存処理
      store() {
        if (process.env.MIX_DEBUG) console.log("User Dialog func store")

          // サーバへ送る情報を設定
        var params = new URLSearchParams()
        params.append('loginid', this.items.loginid)
        params.append('name', this.items.name)
        params.append('pass', this.items.pass)
        params.append('role', (this.items.role ? 5 : 10))
        params.append('type', this.type)
        // エラー領域を初期化
        this.clearError()
        // サーバの保存処理をajaxで呼び出し
        axios.post('/api/admin/user/store', params)
        // サーバ処理が正常終了したら、メッセージを表示してダイアログを閉じる
        .then( function (response) {
          this.$emit('reload')
          alert(this.items.name + "を保存しました")
          this.close()  // 保存が正常終了したら閉じる
        }.bind(this))
        // サーバ処理でエラーが発生したら。。
        .catch(function (error) {
          if (process.env.MIX_DEBUG) console.log("User Dialog store error")
          console.log(error)
        // 401,419 エラーならログアウト（多分タイムアウトとか）
          if (error.response && [401, 419].includes(error.response.status)) {
            this.$emit('axios-logout')
          }
          // 423 エラーならログインIDの重複を検知したのでメッセージ表示
          else if (error.response && [423].includes(error.response.status)) {
            this.$emit('setsearch', this.items.loginid)
            alert(error.response.data.message)
            return
          }
          // 422 エラーならサーバ側のデータチェック処理エラー発生
          else if (error.response && [422].includes(error.response.status)) {
            alert(error.response.data.message)
            if (error.response.data.errors) {
              for (let key in this.error) {
                if (error.response.data.errors[key]) {
                  this.error[key] = error.response.data.errors[key]
                }
              }
            }
            // エラーが発生した場合はダイアログを閉じない
            return
          }
          this.close()
        }.bind(this))
      },
      destroy() {
        if (process.env.MIX_DEBUG) console.log("User Dialog func destroy")
        var params = new URLSearchParams()
        params.append('loginid', this.items.loginid)
        axios.post('/api/admin/user/destroy', params)
        .then( function (response) {
          this.$emit('reload')
          alert(this.items.name + "\n" + "を削除しました")
          this.close()  // 保存が正常終了したら閉じる
        }.bind(this))
        .catch(function (error) {
          if (process.env.MIX_DEBUG) console.log("User Dialog destroy error")
          console.log(error)
          if (error.response && [401, 419].includes(error.response.status)) {
            this.$emit('axios-logout')
          }
          if (error.response && [422].includes(error.response.status)) {
            alert(error.response.data.message) 
            return
          }
          this.close()
        }.bind(this))
      },
    },
  }
</script>