@isset($isResponsive)
    <div class="table-responsive">
@endisset
        <table class="table {{ $class ?? '' }}">
            <thead>
                <tr>
                    {!! $columns !!}
                </tr>
            </thead>
            <tbody>
                {!! $rows !!}
            </tbody>
        </table>
@isset($isResponsive)
   </div>
@endisset



