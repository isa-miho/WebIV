@if($status == 'Paga')
    <span class="badge text-bg-success">Paga</span>
@elseif($status == 'Vencida')
    <span class="badge text-bg-danger">Vencida</span>
@else
    <span class="badge text-bg-secondary">A vencer</span>
@endif
