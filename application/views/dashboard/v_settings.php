<div class="page active">
    <div style="max-width: 800px;">

        <div style="margin-bottom:24px;">
            <div style="font-family:'Syne',sans-serif; font-weight:800; font-size:20px; color: var(--cream);">
                Pengaturan Studio
            </div>
            <div style="font-size:12px; color: var(--smoke); margin-top:4px;">
                Konfigurasi logika penjadwalan dan parameter operasional bisnis.
            </div>
        </div>

        <?php if ($this->input->get('alert') == 'settings_saved'): ?>
            <div class="alert" style="background:rgba(74,222,128,0.1); border-color:rgba(74,222,128,0.3); color:#4ade80; margin-bottom:20px;">
                ✓ &nbsp;Pengaturan diperbarui. Jadwal telah dihitung ulang secara otomatis.
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo base_url('dashboard/save_settings'); ?>">
            
            <?php 
            // Group settings by their group column
            $groups = [];
            foreach ($settings as $s) {
                $groups[$s->group][] = $s;
            }
            ?>

            <?php foreach ($groups as $group_name => $items): ?>
                <div class="panel" style="margin-bottom:24px;">
                    <div class="panel-header">
                        <div class="panel-title" style="text-transform:uppercase; letter-spacing:0.1em; font-size:11px;">
                            Konfigurasi <?php echo $group_name; ?>
                        </div>
                    </div>
                    <div style="padding:24px;">
                        
                        <?php foreach ($items as $s): ?>
                            <div class="form-group" style="margin-bottom:28px;">
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:8px;">
                                    <label class="form-label" style="margin-bottom:0; font-weight:600; color:var(--cream);">
                                        <?php echo $s->label; ?>
                                    </label>
                                    <div style="font-size:10px; color:var(--smoke); background:var(--ash); padding:2px 6px; border-radius:3px;">
                                        <?php echo $s->key; ?>
                                    </div>
                                </div>
                                
                                <input 
                                    type="<?php echo ($s->type == 'time') ? 'time' : 'text'; ?>" 
                                    name="<?php echo $s->key; ?>" 
                                    value="<?php echo htmlspecialchars($s->value); ?>" 
                                    class="form-input"
                                    style="margin-bottom:6px;"
                                >
                                
                                <div style="font-size:11px; color:var(--smoke); line-height:1.5;">
                                    <span style="color:var(--ember);">ℹ</span> <?php echo $s->description; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>
            <?php endforeach; ?>

            <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:8px;">
                <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                    Simpan Semua Pengaturan →
                </button>
            </div>

        </form>

    </div>
</div>
