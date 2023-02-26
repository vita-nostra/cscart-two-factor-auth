{assign var="id" value=$id|default:"main_login"}

{capture name="login"}
    <form name="{$id}_form" action="{""|fn_url}" method="post" {if $style == "popup"}class="cm-ajax cm-ajax-full-render"{/if}>
        {if $style == "popup"}
            <input type="hidden" name="result_ids" value="{$id}_login_popup_form_container" />
            <input type="hidden" name="login_block_id" value="{$id}" />
            <input type="hidden" name="quick_login" value="1" />
        {/if}

        <input type="hidden" name="return_url" value="{$smarty.request.return_url|default:$config.current_url}" />
        <input type="hidden" name="redirect_url" value="{$redirect_url|default:$config.current_url}" />

        {if $style == "checkout"}
        <div class="ty-checkout-login-form">{include file="common/subheader.tpl" title=__("returning_customer")}
            {/if}

            <div class="ty-control-group">
                <label for="confirm_code" class="ty-login__filed-label ty-control-group__label cm-required cm-trim">{__("two_factor_auth.popup.confirmation_code")}</label>
                <input type="text" id="confirm_code" name="confirm_code" size="30" value="" class="ty-login__input cm-focus" />
            </div>

            {if $style == "popup"}
                {if $code_info}
                    <div class="ty-login-form__wrong-credentials-container">
                        <span class="ty-login-form__wrong-credentials-text ty-error-text">{$code_info}</span>
                    </div>
                {/if}
            {/if}

            {if $style == "checkout"}
        </div>
        {/if}

            <div class="buttons-container clearfix">
                <div class="ty-float-left">
                    <a href="{"auth.repeat_confirm_code"|fn_url}" class="ty-password-forgot__a {if $style == "popup"}cm-ajax{/if}"  tabindex="5">{__("two_factor_auth.popup.repeat_code")}</a>
                </div>
                <div class="ty-float-right">
                    {include file="buttons/login.tpl" but_name="dispatch[auth.code]" but_role="submit"}
                </div>
            </div>
    </form>
{/capture}

{if $style == "popup"}
    <div id="{$id}_login_popup_form_container">
        {$smarty.capture.login nofilter}
        <!--{$id}_login_popup_form_container--></div>
{else}
    <div class="ty-login">
        {$smarty.capture.login nofilter}
    </div>

    {capture name="mainbox_title"}{__("sign_in")}{/capture}
{/if}