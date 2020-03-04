{% if paginate.total_pages > 1 %}
    <p class="pull-left">
        Showing <strong>{{ paginate.current }}</strong> to <strong>{{ paginate.items|length }}</strong> of <strong>{{  paginate.total_items }}</strong> entries
    </p>
    <div class="btn-toolbar pull-right">
        <div class="btn-group" role="group">
            {% if paginate.current > 1 %}
                {% set queryParams[pageName] = paginate.before %}
                {% set urlSlug = prefix~QBuilder.build(queryParams) %}
                <a class="btn btn-default" href="{{ url(urlSlug) }}">Prev</a>
            {% endif %}

            {% set reserve =  2 %}
            {% set reserveRange =  5 %}

            {% set startP = paginate.current - reserve %}
            {% if startP <= 0 %}
                {% set startP = 1 %}
            {% endif %}

            {% set countP =  startP + reserveRange -1 %}
            {% if countP > paginate.last %}
                {% set countP = paginate.last %}
            {% endif %}

            {% for i in startP..countP %}
                {% set queryParams[pageName] = i %}
                {% set urlSlug = prefix~QBuilder.build(queryParams) %}
                <a class="btn btn-default {{ paginate.current == i ? 'active':'' }}"
                   href="{{ url(urlSlug) }}">{{ i }}</a>
            {% endfor %}

            {% if paginate.last - paginate.current > 1 %}
                {% set queryParams[pageName] = paginate.last %}
                {% set urlSlug = prefix~QBuilder.build(queryParams) %}
                <a class="btn btn-default" href="{{ url(urlSlug) }}">Last</a>
            {% endif %}

            {% if paginate.last - paginate.current >= 1 %}
                {% set queryParams[pageName] = paginate.next %}
                {% set urlSlug = prefix~QBuilder.build(queryParams) %}
                <a class="btn btn-default" href="{{ url(urlSlug) }}">Next</a>
            {% endif %}
        </div>
    </div>
{% endif %}
