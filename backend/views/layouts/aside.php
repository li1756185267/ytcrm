<style>
.el-menu-item:link { background:#2A323F !important;}     /* 未访问的链接 */
.el-menu-item:visited { background:#2A323F !important;}  /* 已访问的链接 */
.el-menu-item:hover{ background:#2A323F !important;}
.el-menu-item:active { background:#2A323F !important;}   /* 被选择的链接 */
.el-menu-item.is-active { background: #2A323F !important;}
.el-menu-item i { color: #fff;}
</style>
<div class="CenterMain el-col el-col-24">
    <section class="el-container">
<!-- <el-col :span="24" class="CenterMain">
    <el-container> -->
    <div id="aside">
    <template>
        <el-aside :class="collapsed ? 'menu-collapsed' : 'menu-expanded'">
            <!-- <div class="home"><a href="/backend.php?r=index"><i class="fa fa-home"></i>首页</a></div> -->
            <el-menu :default-active="active" class="el-menu-vertical-demo" @open="handleopen" @close="handleclose" :collapse="isCollapse" :collapse-transition=false>
                <?php
                    $index = 0;
                    $menus = $this->context->menu;
                    foreach ($menus as $ke=>$menu) {
                        if (isset($menu['items'])) {
                            $index ++;
                            echo
                                '
                                <el-submenu index="'.$index.'">
                                    <template slot="title">
                                        <i class="'.$menu['icon'].'"></i>'. $menu['label'].'
                                    </template>
                                    ';
                            $childIndex = 0;
                            echo '<el-menu-item-group>';
                            foreach ($menu['items'] as $child) {
                                $childIndex ++;
                                $url = "?r={$child['url'][0]}";
                                $setIndex = $index.'-'.$childIndex;
                                echo '
                                    <el-menu-item v-on:click=setIndex('."'".$setIndex."'".','."'".$url."'".') data-index="'.$index.'-'.$childIndex.'" index="'.$index.'-'.$childIndex.'">
                                        <a hrqef="?r='.$url.'">'.$child['label'].'</a>
                                    </el-menu-item>
                                ';
                            };
                            echo '</el-menu-item-group>';
                            echo '
                            </el-submenu>
                            ';
                        } else {
                            $url = "?r={$menu['url']}";
                            echo '
                            <el-menu-item index="'.++$index.'-1"  v-on:click=setIndex('."'".$index."-1'".','."'".$url."'".') data-index="'.$index.'-1">
                                <a hrqef="?r=?r='.$menu['url'].'">
                                    <i class="'.$menu['icon'].'"></i>
                                    <span slot="title">'.$menu['label'].'</span>
                                </a>
                            </el-menu-item>
                            ';
                        }
                    }
                    ?>
            </el-menu>
        </el-aside>
        </template>
    </div>
    <!-- <el-main :class="aside.collapsed ? 'main-collapsed' : 'main-expanded'"> -->
        <main class="yt-main el-main main-expanded" >
            <?=$content?>
        </main>
    <!-- </el-main> -->

    <!-- </el-container>
</el-col> -->
</section>
</div>

<script>
	var aside = new Vue({
		el: "#aside",
		data: {
			collapsed: false,
            isCollapse: false,
            active: '',
            className:'',
            changeName:'',
        },
        mounted: function(){
            const index = Yt.getCookie('index');
            if (index) {
                this.active = index;
            }else{
                this.active="1-1" //当打开页面时，侧边栏直接显示智能语音-任务管理
            }
            const collapsed = Yt.getCookie('collapsed')
            if ( !collapsed || collapsed === "false" ) {
                this.collapsed = false
                this.isCollapse = false
            } else {
                this.collapsed = true
                this.isCollapse = true
            }
        },
		methods: {
            change(){
                // this.isCollapse = true
                // Yt.setCookie('index', "-1");
               
            },
			handleopen() {
                // console.log("打开了")
            },
			handleclose() {
                // console.log("关闭了")
            },
            setIndex(index, url){
                this.active = index;
                Yt.setCookie('index', index);
                Yt.setCookie('message',6)
                Yt.go(url);
            }
        },
        watch: {
            collapsed: function(){
                Yt.setCookie('collapsed', this.collapsed, '10d');
                if ( this.collapsed ) {
                    this.className = "main-expanded";
                    this.changeName = "main-collapsed";
                } else {
                    this.className = "main-collapsed";
                    this.changeName = "main-expanded";
                }
            },
            className: function(){
                const classLists = document.querySelector(".yt-main").classList;
                classLists.remove(this.className);
                classLists.add(this.changeName);
            }
        }
	})
</script>