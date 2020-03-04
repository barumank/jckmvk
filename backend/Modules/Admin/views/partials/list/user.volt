<div class="table-responsive">
    <table class="table table-striped projects">
        <thead>
        <tr>
            <th width="100px">#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th><a href="{{ sortHelper.getSortLink('auth_date') }}">Sign Up Date {{ sortHelper.getSortIcon('auth_date',sortIconFactory) }}</a></th>
            <th>Role</th>
            <th><a href="{{ sortHelper.getSortLink('has_active') }}">Status {{ sortHelper.getSortIcon('has_active',sortIconFactory) }}</a></th>
            <th width="100px"></th>
        </tr>
        </thead>
        <tbody>
        {% for item in paginate.items %}
            {% set userItem = user.setUser(item['u']).setUserProperty(item['up']) %}
            <tr>
                <td>{{ userItem.getId() }}</td>
                <td>{{ userItem.getName() }}</td>
                <td>{{ userItem.getEmail() }}</td>
                <td>{{ userItem.getPhone() }} </td>
                <td>{{ dateFormatter.setDate(userItem.getAuthDate()).formatted() }} </td>
                <td>{{ userItem.getRoleLabel() }} </td>
                <td>
                    {% if userItem.getIsBlocked() %}
                        <i class="fa fa-times-circle userlist__status__icon__disabled"></i>
                    {% else %}
                        <i class="fa fa-check-circle userlist__status__icon__enabled"></i>
                    {% endif %}
                </td>
                <td>
                    <div class="btn-group pull-right">
                        <a href="{{ url('/admin/user/edit/'~ userItem.getId()) }}"
                           class="btn btn-info btn-xs">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <a class="btn btn-danger btn-xs" data-toggle="modal" data-target=".delete-item"
                           data-action="{{ url('/admin/user/delete/'~userItem.getId()) }}"
                           data-name="{{ userItem.getName()|escape }}">
                            <i class="fa fa-close"></i>
                        </a>
                    </div>
                </td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
</div>