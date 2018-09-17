<link rel="stylesheet" href="<?=STATIC_BACKEND?>/common/common.css">
<link rel="stylesheet" href="<?=STATIC_BACKEND?>/css/edit-personal.css">
<style>
    .el-dropdown-link {position:relative}
    .el-dropdown-link em{position:absolute;top:-5px; right:-8px; 
    width:15px; height:15px;border-radius:15px;background:#fb0023;color:#fff; text-align:center;line-height:15px;font-size:12px}
</style>
<div id="head">
    <template>
        <el-header>
            <div class="logo fl">
            <a href="javascript:;" title="首页" alt="首页"> <img src="<?=STATIC_COMMON?>/images/logo.png"></a>
            </div>
            <div class="toos fl" @click="collapse">
                <i class="fa fa-reorder"></i>
            </div>
            <div class="userinfo fr">
                <div class="refresh fl" v-show="showBack">
                    <img src="<?=STATIC_BACKEND?>/images/back.png" / style="width:20px;vertical-align: middle;">
                    <a href="sysadmin.php?r=companyinfo/index"> 切回管理</a>
                </div>
                <!-- <div class="refresh fl">
                    线路状态：<em class="success">正常</em><em class="red">不通</em>
                </div>-->
                <div class="refresh fl">
                    <a href=""> 账户名称：<em class="red" style="font-weight: 800 ">{{name}}</em></a>
                </div> 
                <div class="refresh fl">
                    <a href=""> 账户话费余额：<em class="red" style="font-weight: 800 ">{{money}}</em>元</a>
                </div> 
                <div class="refresh fl" @click="reload" style="cursor: pointer">
                    <i class="fa fa-refresh" ></i>刷新
                </div>
                
                <el-dropdown trigger="hover" @command="handleCommand" style="border-right:1px solid #f2f2f2; padding-right:10px; height:44px">
                    <span class="el-dropdown-link userinfo-inner">
                        <i class="fa fa fa-commenting-o"></i> 通知
                        <em  v-show="showNum">{{num}}</em>
                    </span>
                    <el-dropdown-menu slot="dropdown"  style="width:300px">
                        <el-dropdown-item style="width: 255px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;" v-for="(item,index) in infos"><a href="backend.php?r=system-message/index" style="color:#333;display:inline-block" @click="check">{{index+1}}.{{item.content}}</a></el-dropdown-item>
                        <a href="backend.php?r=system-message/index" style="display:block;text-align:center"> <el-button @click="check"> 查看全部通知  </el-button></a>
                    </el-dropdown-menu>
                </el-dropdown>
                <el-dropdown trigger="hover" @command="handleCommand" size="medium">
                    <span class="el-dropdown-link userinfo-inner">
                        <img :src="imageUrl" />{{info.name}}</span>
                    <el-dropdown-menu slot="dropdown">
                        <el-dropdown-item >公司代号：{{company_code}}</el-dropdown-item>
                        <el-dropdown-item command="changeImg">设置</el-dropdown-item>
                        <el-dropdown-item divided command="logout">退出登录</el-dropdown-item>
                    </el-dropdown-menu>
                </el-dropdown>
            </div>
        </el-header>
         <!-- 电话绑定 -->
         <div class="dial-header linkNumber">
            <el-dialog title="请绑定手机号" :visible.sync= "linkPhone" :show-close = false :close-on-press-escape = false :close-on-click-modal = false>
            <el-form :model="formLabelAlign"  ref="formLabelAlign">
                <el-form-item 
                    prop="phone"
                    :rules="[
                        { required: true, message: '请输入手机号', trigger: 'blur' }
                    ]"
                >
                    <el-input v-model="formLabelAlign.phone" placeholder="请输入手机号"autofocus = true></el-input>
                </el-form-item>
                <el-form-item
                    prop="code"
                    :rules="[
                        { required: true, message: '请输入验证码', trigger: 'blur' }
                    ]"
                >
                    <el-input v-model="formLabelAlign.code" placeholder="短信验证码"></el-input>
                    <el-button round class="btnPosition"type="primary" @click="sendCode">
                        <span v-show="!startBtn">发送验证码</span>
                        <span v-show="startBtn">( {{endNumber}} )</span>
                    </el-button>
                </el-form-item>
            </el-form>
                <div slot="footer" class="dialog-footer"> 
                    <el-button class="sureBtn"type="primary" @click="submitForm('formLabelAlign')" round>确认</el-button> 
                </div>
            </el-dialog>
        </div>
    </template>
</div>
<script>
    var head = new Vue({
        el: "#head",
        data:{
            // 以下电话绑定参数
            linkPhone:false,
            formLabelAlign: {
                phone:'',
                code:''
            },
            startBtn: false,
            endNumber: 60,
            timer:null,
            phone:'',
            user_name:'',
            sys_login:'',
            // 以上电话绑定参数
            company_code:'',
            showBack:true,
            info:{
                name:""
            },
            imageUrl: '',
            dialogImageUrl: '',
            dialogVisible: false,
            oploadTime:true,
            showNum:true,
            infos:[],
            page:"1",
            page_size:"5",
            num:"",
            money:"",  //余额
            name:"",
        },
        mounted(){
            this.get()
            this.init()
            this.initMessage()
            this.mon()
        },
        watch:{
            startBtn(){
                if(head.startBtn){
                    head.timer = setInterval(()=>{
                        head.endNumber --
                        if(head.endNumber == 0){
                            head.startBtn = false
                            clearInterval(head.timer)
                            head.endNumber = 60
                        }
                    },1000)
                }
            },
        },
        methods: {
            // 余额
            mon(){
                const url = "api_backend.php?r=asrcall-bill/balance-info"
                const conf = {
                    url,
                    success:(data)=>{
                        this.money = data.money
                        this.name = data.name
                    }
                }
                Yt.axiosRequest(conf)
            },
            // 系统信息
            initMessage(){
                const page = this.page
                const page_size = this.page_size
                const url = "/api_backend.php?r=message-inform/system-message"
                const conf = {
                    url,
                    data:{
                        page,
                        page_size
                    },
                    success:(data)=>{
                        this.infos = data.info.info
                        this.num = Number(data.info.new_message)
                        if(this.num == 0){
                            this.showNum = false
                        }
                    }
                }
                Yt.axiosRequest(conf)
            },
            // 查看全部信息
            check(){
                Yt.setCookie("message",6)
                Yt.setCookie("index", "9-1")
            },
            submitForm(formName) {
                head.$refs[formName].validate((valid) => {
                    if (valid) {
                        if( head.isPoneAvailable(head.formLabelAlign.phone) ){
                            // 传入手机号和验证码
                            const conf = {
                                url:"api_backend.php?r=index/mobile",
                                data:{
                                    mobile: head.formLabelAlign.phone,
                                    code: head.formLabelAlign.code
                                },
                                success:(data)=>{
                                    if( data.statusCode == 1 ){
                                        this.$message({
                                            showClose:true,
                                            type: 'success',
                                            message: '绑定成功'
                                        })
                                        head.phone = data.info 
                                        head.formLabelAlign = {}
                                        head.linkPhone = false
                                        personalFile.init()
                                    }else if( data.statusCode == 0 ){
                                        this.$message({
                                            showClose:true,
                                            type: 'warning',
                                            message: data.message
                                        })
                                        return
                                    }
                                }
                            }
                            Yt.axiosRequest(conf)
                        }else{
                            this.$message({
                                showClose:true,
                                type: 'warning',
                                message: '手机号码格式不正确！'
                            })
                            return 
                        }
                    } else {
                        console.log('error submit!!')
                        return false
                    }
                })
            },
            isPoneAvailable(str) {
                var myreg = /^[1][3,4,5,7,8][0-9]{9}$/
                if (!myreg.test(str) ) {
                    return false
                } else {
                    return true
                }
            },
            sendCode(){
                if( head.startBtn ){
                    return
                }
                if(!head.formLabelAlign.phone.trim()){
                    this.$message({
                        showClose:true,
                        type: 'warning',
                        message: '请输入手机号'
                    })
                }else{
                    if( head.isPoneAvailable(head.formLabelAlign.phone) ){
                        const conf = {
                            url:"api_backend.php?r=index/sms",
                            data:{
                                mobile:head.formLabelAlign.phone
                            },
                            success:(data)=>{
                                if(data.statusCode == 1){
                                    this.$message({
                                        showClose:true,
                                        type: 'success',
                                        message: '验证码发送成功，请注意查收短信！'
                                    })
                                    head.startBtn = true
                                }else if(data.statusCode == 0){
                                    this.$message({
                                        showClose:true,
                                        type: 'warning',
                                        message: data.message
                                    })
                                }
                            }
                        }
                        Yt.axiosRequest(conf)
                    }else{
                        this.$message({
                            showClose:true,
                            type: 'warning',
                            message: '手机号码格式不正确！'
                        })
                        return 
                    }
                }
            },
            reload(){
                location.reload()
            },
            // 进入页面获取公司信息
            init(){
                this.info.name = "<?php echo $this->context->ytuser['true_name']; ?>"
                this.imageUrl = "<?php echo isset($this->context->ytuser['pic']) ? $this->context->ytuser['pic'] : ''; ?>"
                this.user_name = "<?php echo $this->context->ytuser['true_name']; ?>"
                this.phone = "<?php echo isset($this->context->ytuser['phone']) ? $this->context->ytuser['phone'] : ''; ?>"
                this.company_code = "<?php echo isset($this->context->ytuser['prefix']) ? $this->context->ytuser['prefix'] : ''; ?>"
                this.sys_login = "<?php echo isset($this->context->ytuser['sys_login']) ? $this->context->ytuser['sys_login'] : ''; ?>"
                if( ( this.phone == null || this.phone == '') && (this.sys_login != 1 ) ){
                    this.linkPhone = true
                }
            },
            get(){
                if(<?php echo $this->context->ytuser['sys_login']; ?> == 1){
                    this.showBack = true
                }else{
                    this.showBack = false
                }
            },
            collapse: function (){
                aside.collapsed = !aside.collapsed
                aside.isCollapse = !aside.isCollapse
            },
            handleCommand: function (command) {
                switch (command) {
                    case "logout":
                        this.$options.methods.logout();
                        break;
                    case "changeImg":
                        this.$options.methods.changeImg();
                        break;
                    default:
                        break;
                }
            },
            logout: function () {
                const url = '/api_backend.php?r=index/logout'
                axios.post(url).then(function (data) {
                    if (data.data.statusCode == 0) {
                        vm.$message({ message: data.data.Message, type: 'error' })
                        return false;
                    } else {
                        Yt.setCookie('index','1-1')
                        // 跳转首页
                        Yt.goHome()
                    }
                }).catch(function (data) {

                })
            },
            // 更换头像
            changeImg(){
                Yt.setCookie('index','5-5')
                Yt.goHome("?r=edit-personal")
            }
        }
    })
</script>
