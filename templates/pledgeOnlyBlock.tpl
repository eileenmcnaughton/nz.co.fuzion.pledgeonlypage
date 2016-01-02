{if $pledgeBlock}
  {if $is_pledge_payment}
    <div class="crm-section {$form.pledge_amount.name}-section">
      <div class="label">{$form.pledge_amount.label}&nbsp;<span class="marker">*</span></div>
      <div class="content">{$form.pledge_amount.html}</div>
      <div class="clear"></div>
    </div>
  {else}
    <div class="crm-section {$form.is_pledge.name}-section">
      <div class="label">{$form.pledge_start_date.label}&nbsp;</div>
      <div class="content">
        {include file="CRM/common/jcalendar.tpl" elementName=pledge_start_date}
      </div>
      <div class="label">&nbsp;</div>
      <div class="content">
        <span class="crm-pledge-option">
          {$form.is_pledge.html}&nbsp;
        </span>
        <span class="crm-pledge-multi">Every
        {if $is_pledge_interval}
          {$form.pledge_frequency_interval.html}&nbsp;
        {/if}
        {$form.pledge_frequency_unit.html}</span><span id="pledge_installments_num">&nbsp;<span class="crm-pledge-multi">{ts}for{/ts}</span>&nbsp;{$form.pledge_installments.html}&nbsp;{ts}installment<span class="crm-pledge-multi">s</span>{/ts}</span>
      </div>
      <div class="clear"></div>
    </div>
  {/if}
  {literal}
    <script type="text/javascript">
      function showHideConfusingInstallmentDetail() {
        var installments = cj('#pledge_installments').val();
        console.log(installments);
        cj('.crm-pledge-option').hide();
        if (parseInt(installments) == 1) {
          cj('.crm-pledge-multi').hide();
        }
        else {
        cj('.crm-pledge-multi').show();
        }
      }
      cj('#pledge_installments').on('keyup', function(){showHideConfusingInstallmentDetail()});
      showHideConfusingInstallmentDetail();
    </script>
  {/literal}
{/if}

