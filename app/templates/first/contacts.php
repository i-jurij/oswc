<div class="he_soz_tlf flex">
        <div class="he_soz">
            <?php
                if (isset($data['soc']['telegram']) && !empty($data['soc']['telegram'])) {
                    print '<a href="tg://resolve?domain='.$data['soc']['telegram'].'" title="Telegram" class="he_soz-tg" target="_blank" rel="noopener"></a>';
                }
                if (isset($data['soc']['vk']) && !empty($data['soc']['vk'])) {
                    print '<a href="https://vk.com/'.$data['soc']['vk'].'" title="Вконтакте" class="he_soz-vk" target="_blank" rel="noopener"></a>';
                }
            ?>
        </div>

        <div class="he_tlf">
            <?php
                if (isset($data['tel']) && !empty($data['tel'])) {
                    foreach ($data['tel'] as $tel) {
                        print '<a href="tel:'.$tlf.'">'.$tlf.'</a>';
                    }
                }
                else {
                    print '<a href="tel:+7 523 425 25 43">+7 523 425 25 43</a>';
                }
            ?>
        </div>
    </div>

    <div class="he_adres">
        <?php
            if (isset($data['adres']) && !empty($data['adres'])) {
                if (isset($data['map']) && !empty($data['map'])) {
                    print '<a class="he_adres_a" href="'.$data['map'].'">'.$data['adres'].'</a>';
                }
                else {
                    print '<span class="he_adres_a">'.$data['adres'].'</span>';
                }
                
            }
        ?>        
    </div>