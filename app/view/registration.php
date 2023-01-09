<div class="div_center" style="max-width:30rem;">
    <form method="post" action="" name="reg_form" class="form back shad rad pad mar" id="login_form">
        <div class="form-element">Enter data for registration</div>
            <div class="form-element mar">
                <label class="">Name (3-25 letter, digits)</label><br />
                <input type="text" name="reg_name" minlength="3" maxlength="25" pattern="[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}" placeholder="Petia" required />
            </div>

            <div class="form-element mar">
                <label class="">Password (4-120 symbols)</label><br />
                <input type="password" name="reg_password" id="password" minlength="4" maxlength="120" placeholder="!Sidorow%^!" required />
            </div>

            <div class="form-element mar form_radio_btn">
                <span>Select the status</span><br />
                <!-- <input type="text" name="status" id="status" placeholder="Status" pattern="" required /> -->
                <label class=""><input type="radio" name="reg_status" id="admin" value="admin" required /><span>admin</span></label>
                <label class=""><input type="radio" name="reg_status" id="moder" value="moder" required /><span>moder</span></label>
                <label class=""><input type="radio" name="reg_status" id="user" value="user" required /><span>user</span></label>
            </div>

            <div class="form-element mar">
                <button type="submit" class="buttons sub">Add</button>
                <button type="reset" class="buttons res">Reset</button>
            </div>
    </form>
</div>
