<div class="box-header with-border">
    <h3 class="box-title"></h3>
    <div class="box-tools">
        <?= anchor(base_url('admin/home/blog_ae'), '<i class = "fa fa-envelope-o mr10"></i> Add New Blog', ['class' => 'btn bg-blue btn-flat btn-sm']) ?>
    </div>

</div>
<div class = "box-body">
    <?= $this->paginator->dispaly_page_record_ipp() ?> 
    <div class="table-responsive">
        <table class="table pt10 table-bordered responsive-table">
            <thead>
                <tr>
                    <th>#</th>
                </tr>
            </thead> 
        </table>
    </div>
</div>
<div class="box-footer clearfix">
    <?= $this->paginator->display_jump_menu_pages() ?>
</div>
