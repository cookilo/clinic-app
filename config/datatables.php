<?php

// File: config/datatables.php
return [
    'buttons' => [
        'edit' => '<button data-toggle="modal" data-target="#editForm" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Ch">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>',
        'delete' => '<button class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="confirmDelete();" title="Delete">
                          <i class="fa fa-lg fa-fw fa-trash"></i>
                      </button>',
        'details' => '<button data-toggle="modal" data-target="#detail" class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                           <i class="fa fa-lg fa-fw fa-eye"></i>
                       </button>',
    ],
    'config' => [
        'order' => [[1, 'asc']],
        'columns' => [null, null, null, ['orderable' => false]],
        'searching' => false,
        'dom' => '<"row" <"col-sm-7" B> <"col-sm-5 d-flex justify-content-end" l> >
                          <"row" <"col-12" tr> >
                          <"row" <"col-sm-6 d-flex justify-content-start" i> <"col-sm-6 d-flex justify-content-end" p> >',
        'language' => [
            'url' => '/vendor/datatables/lang/vi.json',
        ],
    ],
];

