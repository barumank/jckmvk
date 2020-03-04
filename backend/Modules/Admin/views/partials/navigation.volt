<div class="top_nav">
    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle" data-type="{{ sidebarViewType == 0 ? 1 :0 }}">
                    <i class="fa fa-bars"></i>
                </a>
            </div>

            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        {{ auth.getUser().getName() }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li><a href="#">Your profile</a></li>
                        <li><a href="{{ url(['for':'admin.auth.logout']) }}"><i class="fa fa-sign-out pull-right"></i>
                                Exit</a></li>
                    </ul>
                </li>
                {#<li role="presentation" class="dropdown">#}
                {#<a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="true">#}
                {#<i class="fa fa-bullhorn"></i>#}
                {#{% if notice['count'] > 0 %}#}
                {#<span class="badge bg-green">{{ notice['count'] }}</span>#}
                {#{% endif %}#}
                {#</a>#}
                {#{% if notice['count'] > 0  %}#}
                {#<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">#}
                {#{% for message in notice['messages'] %}#}
                {#{{ partial( 'notification', [ 'message' : message ] ) }}#}
                {#{% endfor %}#}
                {#<li>#}
                {#<div class="text-center">#}
                {#<a>#}
                {#<strong>See All Alerts</strong>#}
                {#<i class="fa fa-angle-right"></i>#}
                {#</a>#}
                {#</div>#}
                {#</li>#}
                {#</ul>#}
                {#{% else %}#}
                {#<ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">#}
                {#<li>#}
                {#<div class="text-center">#}
                {#<a>#}
                {#<i class="fa fa-close"></i>#}
                {#<strong>У вас нет уведомлений</strong>#}
                {#</a>#}
                {#</div>#}
                {#</li>#}
                {#</ul>#}
                {#{% endif %}#}
                {#</li>#}
            </ul>
        </nav>
    </div>
</div>