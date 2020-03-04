{{ partial('modal/delete') }}
<div class="">
    <div class="page-title">
        <div class="title_left">
            <h3>List of users</h3>
        </div>
        <form class="title_right" action="/admin/user/list" method="get">
            <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                <div class="input-group">
                    {% set builderFilter = userService.getBuilderFilter() %}
                    <input type="text" name="search" class="form-control" placeholder="Search..."
                           value="{{ builderFilter.getSearch() }}">
                    <span class="input-group-btn">
                        <button class="btn btn-default">To find</button>
                    </span>
                </div>
            </div>
        </form>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>List</h2>
                    <div class="btn-group pull-right">
                        <a href="/admin/user/unloading" class="btn btn-success">
                            <i class="fa fa-file-excel-o"></i> Export to excel</a>
                        <a href="{{ url('/admin/user/create') }}" class="btn btn-success">
                            <i class="fa fa-plus"></i> Add</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    {% if  paginate.total_items > 0 %}
                        {{ partial('list/user') }}
                    {% else %}
                        <div class="alert alert-info"><strong>No results were found for this request!</strong></div>
                    {% endif %}
                    {{ paginationService.render() }}
                </div>
            </div>
        </div>
    </div>
</div>