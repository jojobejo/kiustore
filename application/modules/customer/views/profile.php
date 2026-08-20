<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    .profile-action-grid {
        display: grid;
        grid-template-columns: repeat(5, 56px);
        gap: 8px;
        width: max-content;
        max-width: 100%;
        margin: 12px 0 0;
    }

    .profile-action-btn {
        position: relative;
        width: 56px;
        min-height: 58px;
        margin: 0 !important;
        padding: 7px 5px 6px;
        border: 1px solid rgba(11, 175, 154, 0.22);
        border-radius: 8px;
        background: #ffffff;
        box-shadow: 0 8px 18px rgba(11, 175, 154, 0.12);
        color: #0baf9a !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        overflow: hidden;
        text-decoration: none !important;
        transition: transform 180ms ease, box-shadow 180ms ease, color 180ms ease, border-color 180ms ease, background 180ms ease;
    }

    .profile-action-btn::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(11, 175, 154, 0.12), rgba(31, 111, 190, 0.06));
        pointer-events: none;
    }

    .profile-action-btn:hover,
    .profile-action-btn:focus {
        color: #ffffff !important;
        border-color: rgba(11, 175, 154, 0.62);
        background: linear-gradient(135deg, #0baf9a, #1f6fbe);
        transform: translateY(-2px);
        box-shadow: 0 14px 24px rgba(11, 175, 154, 0.2);
    }

    .profile-action-icon {
        position: relative;
        z-index: 1;
        width: 22px;
        height: 22px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
    }

    .profile-action-icon svg,
    .profile-action-icon i {
        width: 19px;
        height: 19px;
        font-size: 19px;
        color: currentColor;
    }

    .profile-action-text {
        position: relative;
        z-index: 1;
        max-width: 100%;
        color: currentColor;
        display: block;
        font-size: 9.5px;
        font-weight: 800;
        line-height: 1.1;
        letter-spacing: 0;
        text-align: center;
        white-space: nowrap;
    }

    @media (max-width: 360px) {
        .profile-action-grid {
            grid-template-columns: repeat(5, 50px);
            gap: 6px;
        }

        .profile-action-btn {
            width: 50px;
            min-height: 56px;
            padding-left: 3px;
            padding-right: 3px;
        }

        .profile-action-text {
            font-size: 9px;
        }
    }
</style>
<!-- Main Start -->
<main class="main-wrap setting-page mb-xxl">
    <div class="user-panel">
        <div class="media">
            <div class="avatar-wrap">
                <a href="javascript:void(0)"> <img src="<?= get_user_image(); ?>" alt="<?php echo get_user_name(); ?>"></a>
            </div>

            <div class="media-body">
                <h2 class="title-color"><?php echo get_user_name(); ?></h2>
                <div class="profile-action-grid">
                    <a href="<?= base_url('cus_edit_customer/2') ?>" class="profile-action-btn" aria-label="Reset Data Alamat" title="Reset Data Alamat">
                        <span class="profile-action-icon"><i data-feather="map-pin"></i></span>
                        <span class="profile-action-text">Alamat</span>
                    </a>
                    <a href="<?= base_url('edit_profile_cust/' . get_user_name_id()) ?>" class="profile-action-btn" aria-label="Edit Profile" title="Edit Profile">
                        <span class="profile-action-icon"><i data-feather="edit-3"></i></span>
                        <span class="profile-action-text">Profil</span>
                    </a>
                    <a href="<?= base_url('profile/tutorial') ?>" class="profile-action-btn" aria-label="Tutorial" title="Tutorial">
                        <span class="profile-action-icon"><i data-feather="play-circle"></i></span>
                        <span class="profile-action-text">Tutorial</span>
                    </a>
                    <a href="<?= base_url('profile/guide-book-customer') ?>" class="profile-action-btn" aria-label="Guide Book Customer" title="Guide Book Customer">
                        <span class="profile-action-icon"><i data-feather="book-open"></i></span>
                        <span class="profile-action-text">Panduan</span>
                    </a>
                    <a href="<?= base_url('change_password') ?>" class="profile-action-btn" aria-label="Ganti Password" title="Ganti Password">
                        <span class="profile-action-icon"><i data-feather="lock"></i></span>
                        <span class="profile-action-text">Password</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped" id="tbkonversi_profile" style="background:#ffffff;border-radius:8px;overflow:hidden; text-align: center;">
            <thead style="background:#1f6fbe;color:#ffffff;">
                <tr>
                    <th>Point Total</th>
                    <th>Silver</th>
                    <th>Gold</th>
                    <th>Platinum</th>
                </tr>
            </thead>
            <tbody style="color:#0b2a4a;">
                <tr>
                    <td><?= number_format((int)$point_total_silver) ?></td>
                    <td><?= number_format((int)$point_konv_silver) ?></td>
                    <td><?= number_format((int)$point_konv_gold) ?></td>
                    <td><?= number_format((int)$point_konv_platinum) ?></td>
                </tr>
            </tbody>
        </table>
    </div>

    <?php echo form_open_multipart('customer/profile/edit_name', 'class="custom-form"'); ?>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="text" id="inputName" name="name" value="<?php echo set_value('name', $user->name); ?>" disabled readonly>
    </div>
    <div class="input-box">
        <i class="iconly-Call icli"></i>
        <input class="form-control" type="text" id="inputHP" name="phone_number" value="<?php echo set_value('name', $user->phone_number); ?>" disabled readonly>
    </div>
    <div class="input-box">
        <i data-feather="at-sign"></i>
        <input class="form-control" type="email" id="inputEmail" value="<?php echo set_value('name', $user->email); ?>" disabled readonly>
    </div>
    <div class="input-box">
        <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Nama Toko</span></div>
        <input class="form-control" type="text" id="inputAddr" name="shop_name" value="<?php echo set_value('name', $user->shop_name); ?>" disabled readonly>
    </div>

    <?php if ($user->province_id == '0') :  ?>

        <a href="<?= base_url('cus_edit_customer/1') ?>" class="btn btn-warning w-100">Verifikasi Alamat</a>

    <?php else : ?>
        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger">Alamat Rumah</span></div>
            <input class="form-control" type="text" id="inputAddr" name="address" value="<?php echo set_value('name', $user->shop_address); ?>" disabled readonly>
        </div>

        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-map-marker"></i><span class="badge bg-danger"> Alamat Pengiriman</span></div>
            <input class="form-control" type="text" id="inputAddr" name="shop_address" value="<?php echo set_value('name', $user->alamat_kirim); ?>" disabled readonly>
        </div>

        <div class="input-box">
            <div class="title mb-2"><i class="lni lni-user"></i><span>Foto Profil</span></div>
            <input type="file" class="form-control" id="inputFoto" name="file">
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col">
            <a id="newButtonclose" class="btn btn-secondary w-100" style="display: none;" href="<?= base_url('profile') ?>">Batal</a>
        </div>
        <div class="col">
            <a type="submit" id="newButton" class="btn btn-success w-100" style="display: none;">Simpan Perubahan</a>
        </div>
    </div>

    <?php if ($flash) : ?>
        <p class="text-center text-success"><?php echo $flash; ?></p>
    <?php endif; ?>
    </form>
    <!-- Form Section End -->
</main>
<!-- Main End -->
