<?php

//author    : Ade Rayendra
//email     : ade.rayendra@gmail.com
//phone     : 085763213730
//github    : aderayendra

defined('BASEPATH') or exit('No direct script access allowed');

class M_absen extends CI_Model
{
    function get_event_types()
    {
        return $this->db->get('tpp_jenis_absen')->result();
    }

    function get_oldest_cio_date()
    {
        $this->db->order_by('DATE(tanggal_finger)', 'ASC');
        $this->db->limit(1);
        $query = $this->db->get('tbl_checkinout');
        return $query->num_rows() > 0 ? DateTime::createFromFormat('Y-m-d', $query->first_row()->tanggal_finger) : null;
    }

    function get_users($office_id)
    {
        $this->db->join('tbl_pegawai', 'tbl_pegawai.id_pegawai = tbl_users.id_member');
        $this->db->join('tpp_template', 'tpp_template.id_template = tbl_pegawai.id_template');
        $this->db->where('level', 'pegawai');
        $this->db->where('id_kantor', $office_id);
        $this->db->order_by('nama');
        return $this->db->get('tbl_users')->result();
    }

    function get_events_in_month($office_id, $firstDay, $lastDay)
    {
        $this->db->select('tpp_jadwal_absen.*, tbl_kordinat.*, tpp_jenis_absen.*');
        $this->db->join('tbl_kordinat', 'tbl_kordinat.id_kordinat = tpp_jadwal_absen.id_kordinat');
        $this->db->join('tpp_jenis_absen', 'tpp_jenis_absen.id_jenis_absen = tpp_jadwal_absen.id_jenis_absen');
        $this->db->join('tbl_jenis_kantor', 'tbl_jenis_kantor.id_kantor = ' . $office_id);
        $this->db->where("tpp_jadwal_absen.array_jenis_kantor LIKE CONCAT('%\"', tbl_jenis_kantor.parent_id_jenis_kantor , '\"%')", NULL, FALSE);
        $this->db->where("DATE(tpp_jadwal_absen.tanggal) >= '" . $firstDay . "'", null, false);
        $this->db->where("DATE(tpp_jadwal_absen.tanggal) <= '" . $lastDay . "'", null, false);
        $query = $this->db->get('tpp_jadwal_absen')->result();
        $data = [];
        foreach ($query as $event) {
            $data[$event->tanggal] = $event;
        }
        return $data;
    }

    function get_n_holidays_in_month($firstDay, $lastDay)
    {
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $query = $this->db->get('tpp_libur_nasional')->result();
        $data = [];
        foreach ($query as $nh) {
            $data[$nh->tanggal] = $nh;
        }
        return $data;
    }

    function get_cios_in_month($office_id, $firstDay, $lastDay)
    {

        $this->db->where('k_id', $office_id);
        $this->db->where('DATE(tanggal_finger) >=', $firstDay);
        $this->db->where('DATE(tanggal_finger) <=', $lastDay);
        $query = $this->db->get('tbl_checkinout')->result();
        $data = [];
        foreach ($query as $cio) {
            $data[$cio->nip][$cio->tanggal_finger] = $cio;
        }
        return $data;
    }

    function get_all_cios_in_month($firstDay, $lastDay)
    {

        $this->db->where('DATE(tanggal_finger) >=', $firstDay);
        $this->db->where('DATE(tanggal_finger) <=', $lastDay);
        $query = $this->db->get('tbl_checkinout')->result();
        $data = [];
        foreach ($query as $cio) {
            $data[$cio->nip][$cio->tanggal_finger] = $cio;
        }
        return $data;
    }

    function get_cts_in_month($office_id, $firstDay, $lastDay)
    {
        $this->db->join('tpp_detail_tggl_cuti', 'tpp_detail_tggl_cuti.id_cuti = tpp_cuti.id_cuti');
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $this->db->where('id_status', 1);
        $this->db->where('id_kantor', $office_id);
        $query = $this->db->get('tpp_cuti')->result();
        $data = [];
        foreach ($query as $ct) {
            $data[$ct->nip][$ct->tanggal] = $ct;
        }
        return $data;
    }

    function get_all_cts_in_month($firstDay, $lastDay)
    {
        $this->db->join('tpp_detail_tggl_cuti', 'tpp_detail_tggl_cuti.id_cuti = tpp_cuti.id_cuti');
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $this->db->where('id_status', 1);
        $query = $this->db->get('tpp_cuti')->result();
        $data = [];
        foreach ($query as $ct) {
            $data[$ct->nip][$ct->tanggal] = $ct;
        }
        return $data;
    }

    function get_dls_in_month($office_id, $firstDay, $lastDay)
    {
        $this->db->join('tpp_detail_tggl_dl', 'tpp_detail_tggl_dl.id_dinas_luar = tpp_dinas_luar.id_dinas_luar');
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $this->db->where('id_status_dl', 1);
        $this->db->where('id_kantor', $office_id);
        $query = $this->db->get('tpp_dinas_luar')->result();
        $data = [];
        foreach ($query as $ct) {
            $data[$ct->nip][$ct->tanggal] = $ct;
        }
        return $data;
    }

    function get_all_dls_in_month($firstDay, $lastDay)
    {
        $this->db->join('tpp_detail_tggl_dl', 'tpp_detail_tggl_dl.id_dinas_luar = tpp_dinas_luar.id_dinas_luar');
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $this->db->where('id_status_dl', 1);
        $query = $this->db->get('tpp_dinas_luar')->result();
        $data = [];
        foreach ($query as $ct) {
            $data[$ct->nip][$ct->tanggal] = $ct;
        }
        return $data;
    }

    function get_shifts_in_month($office_id, $firstDay, $lastDay)
    {
        $this->db->where('k_id', $office_id);
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $query = $this->db->get('tbl_shif')->result();
        $data = [];
        foreach ($query as $shift) {
            $data[$shift->nip][$shift->tanggal] = $shift;
        }
        return $data;
    }

    function get_all_shifts_in_month($firstDay, $lastDay)
    {
        $this->db->where('DATE(tanggal) >=', $firstDay);
        $this->db->where('DATE(tanggal) <=', $lastDay);
        $query = $this->db->get('tbl_shif')->result();
        $data = [];
        foreach ($query as $shift) {
            $data[$shift->nip][$shift->tanggal] = $shift;
        }
        return $data;
    }

    function get_kantor()
    {
        $query = $this->db->get('tbl_kantor')->result();
        return $query;
    }
    function get_jenis_absen()
    {
        $query = $this->db->get('tpp_jenis_absen')->result();
        return $query;
    }

    function calculate_and_generate_att_report($datePeriod, $users, $n_holidays, $events, $cios, $cts, $dls, $shifts, bool $withDetails = true)
    {
        $report = [];
        $index = 0;
        foreach ($users as $user) {
            $texts = [];
            $bgcolors = [];
            $hari_kerja = 0;
            $hadir = 0;
            $cuti = 0;
            $dl = 0;
            $tk = 0;
            $tke = 0;

            $tl1 = 0;
            $tl2 = 0;
            $tl3 = 0;
            $tl4 = 0;

            $psw1 = 0;
            $psw2 = 0;
            $psw3 = 0;
            $psw4 = 0;
            foreach ($datePeriod as $date) {
                $yesterdayString = (clone $date)->modify('-1 day')->format('Y-m-d');
                $dateString = $date->format('Y-m-d');
                $single_data = $this->calculate_att_per_day_per_user(
                    $user,
                    $date,
                    isset($n_holidays[$dateString]) ? $n_holidays[$dateString] : null,
                    isset($events[$dateString]) ? $events[$dateString] : null,
                    isset($cios[$user->nip][$dateString]) ? $cios[$user->nip][$dateString] : null,
                    isset($cts[$user->nip][$dateString]) ? $cts[$user->nip][$dateString] : null,
                    isset($dls[$user->nip][$dateString]) ? $dls[$user->nip][$dateString] : null,
                    isset($shifts[$user->nip][$dateString]) ? $shifts[$user->nip][$dateString] : null,
                    isset($shifts[$user->nip][$yesterdayString]) ? $shifts[$user->nip][$yesterdayString] : null
                );

                $texts[] = $single_data['text'];
                $bgcolors[] = $single_data['bgcolor'];
                $hari_kerja += $single_data['hari_kerja'];
                $hadir += $single_data['hadir'];
                $cuti += $single_data['cuti'];
                $dl += $single_data['dl'];
                $tk += $single_data['tk'];
                $tke += $single_data['tke'];

                $tl1 += $single_data['tl1'];
                $tl2 += $single_data['tl2'];
                $tl3 += $single_data['tl3'];
                $tl4 += $single_data['tl4'];

                $psw1 += $single_data['psw1'];
                $psw2 += $single_data['psw2'];
                $psw3 += $single_data['psw3'];
                $psw4 += $single_data['psw4'];
            }

            $report[$index] = [
                'name' => strtoupper($user->nama),
                'nip' => $user->nip,
                'type' => strtoupper($user->jenis),
                'hari_kerja' => $hari_kerja,
                'hadir' => $hadir,
                'cuti' => $cuti,
                'dl' => $dl,
                'tk' => $tk,
                'tke' => $tke,
                'tl1' => $tl1,
                'tl2' => $tl2,
                'tl3' => $tl3,
                'tl4' => $tl4,
                'psw1' => $psw1,
                'psw2' => $psw2,
                'psw3' => $psw3,
                'psw4' => $psw4,
            ];
            if ($withDetails) {
                $report[$index]['bgcolors'] = $bgcolors;
                $report[$index]['texts'] = $texts;
            }
            $index++;
        }

        return $report;
    }

    private function decide_schedule_type($user, $date, $holiday, $event, $user_shift, $user_y_shift)
    {
        $id_days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        if ($user_shift) return 'SH';
        if ($user_y_shift && strtotime($user_y_shift->jadwal_masuk) > strtotime($user_y_shift->jadwal_pulang)) return 'LKSSBH';
        if ($user->apel_dll == 1 && $event) return 'EV';
        if ($user->hari_libur == 1 && $holiday) return 'LN';
        if ($user->{$id_days[intval($date->format('w'))]} == NULL) return 'LTP';
        return 'TP';
    }

    private function calculate_att_per_day_per_user($user, $date, $holiday, $event, $user_cio, $user_ct, $user_dl, $user_shift, $user_y_shift)
    {
        $text = '';
        $bgcolor = null;
        $hari_kerja = 0;
        $hadir = 0;
        $cuti = 0;
        $dl = 0;
        $tk = 0;
        $tke = 0;
        $tl1 = 0;
        $tl2 = 0;
        $tl3 = 0;
        $tl4 = 0;
        $psw1 = 0;
        $psw2 = 0;
        $psw3 = 0;
        $psw4 = 0;

        $schedule_type = $this->decide_schedule_type($user, $date, $holiday, $event, $user_shift, $user_y_shift);

        if (substr($schedule_type, 0, 1) == 'L') {
            $bgcolor = '#aaa';
            $text = $schedule_type == 'LN' ? 'LN' : 'L';
            goto result;
        }

        $hari_kerja = 1;

        if ($user_ct) {
            $text = '<font color="green">CT</font>';
            $cuti = 1;
            goto result;
        }

        if ($user_dl) {
            $text = '<font color="green">DL</font>';
            $dl = 1;
            goto result;
        }

        if (!$user_cio) {
            $tk = 1;
            $bgcolor = '#ffcccc';
            $text = '<font>TK</font>';
            if ($schedule_type == 'EV') {
                $tke = 1;
                $tk = 0;
                $text = '<font>TKE</font>';
            }
            goto result;
        }

        $hadir = 1;
        $in_time = $user_cio->finger_time_in ? strtotime($user_cio->finger_time_in) : null;
        $out_time = $user_cio->finger_time_out ? strtotime($user_cio->finger_time_out) : null;
        $in_schedule = strtotime($user_cio->jadwal_masuk);
        $out_schedule = strtotime($user_cio->jadwal_keluar);

        if (!$in_time) {
            $tl4 = 1;
            $top_text = '<font color="red">&#x2612</font>';
            if ($schedule_type == 'EV') {
                $tke = 1;
                $top_text = '<font color="red">TKE</font>';
                $tl4 = 0;
            }
        } else {
            $color = 'red';
            $ev_text = null;
            if ($schedule_type == 'EV') {
                $ev_end_time = strtotime($event->jam_berakhir);
                if ($in_time > $ev_end_time) {
                    $ev_text  = '<font color="red">TKE</font>';
                    $tke = 1;
                } else {
                    $color = 'green';
                }
            } else {
                $time_diff = 0;
                if ($in_schedule < $out_schedule) {
                    $time_diff = $in_time - $in_schedule;
                } else if ($schedule_type == 'SH' && $in_schedule > $out_schedule && ($in_time > $in_schedule && $in_time > $out_schedule) || ($in_time < $in_schedule && $in_time < $out_schedule)) {
                    $time_diff = ($in_time < $in_schedule ? ($in_time + 86400) : $in_time) - $in_schedule;
                }
                if ($time_diff > 5400) $tl4 = 1;
                else if ($time_diff <= 5400 && $time_diff > 3600) $tl3 = 1;
                else if ($time_diff <= 3600 && $time_diff > 1800) $tl2 = 1;
                else if ($time_diff <= 1800 && $time_diff > 0) $tl1 = 1;
                else $color = 'green';
            }
            $top_text = $ev_text ?? '<font color="' . $color . '">' . $user_cio->finger_time_in . '</font>';
        }

        if (!$out_time) {
            $psw4 = 1;
            $bottom_text = '<font color = "red">&#x2612</font>';
        } else {
            $color = 'red';
            $time_diff = 0;
            if ($in_schedule < $out_schedule) {
                $time_diff = $out_schedule - $out_time;
            } else if ($schedule_type == 'SH' && $in_schedule > $out_schedule && ($out_time > $in_schedule && $out_time > $out_schedule) || ($out_time < $in_schedule && $out_time < $out_schedule)) {
                $out_schedule += 86400;
                $time_diff = ($out_time < $in_schedule ? ($out_time + 86400) : $out_time) - $out_time;
            }

            if ($time_diff > 5400) $psw4 = 1;
            else if ($time_diff <= 5400 && $time_diff > 3600) $psw3 = 1;
            else if ($time_diff <= 3600 && $time_diff > 1800) $psw2 = 1;
            else if ($time_diff <= 1800 && $time_diff > 0) $psw1 = 1;
            else $color = 'green';
            $bottom_text = '<font color = "' . $color . '">' . $user_cio->finger_time_out . '</font>';
        }

        $text = $top_text . '<hr>' . $bottom_text;

        result:
        return [
            'text' => $text,
            'bgcolor' => $bgcolor,
            'hari_kerja' => $hari_kerja,
            'hadir' => $hadir,
            'cuti' => $cuti,
            'dl' => $dl,
            'tk' => $tk,
            'tke' => $tke,
            'tl1' => $tl1,
            'tl2' => $tl2,
            'tl3' => $tl3,
            'tl4' => $tl4,
            'psw1' => $psw1,
            'psw2' => $psw2,
            'psw3' => $psw3,
            'psw4' => $psw4,
        ];
    }

    function calculate_and_generate_ev_report($users, $n_holidays, $events, $cios, $cts, $dls, $shifts)
    {
        $report = [];
        foreach ($users as $user) {
            $details = [];
            foreach ($events as $event) {
                $date = DateTime::createFromFormat('Y-m-d', $event->tanggal);
                $yesterdayString = (clone $date)->modify('-1 day')->format('Y-m-d');
                $dateString = $date->format('Y-m-d');
                $single_data = $this->calculate_event_per_user(
                    $user,
                    $date,
                    isset($n_holidays[$dateString]) ? $n_holidays[$dateString] : null,
                    $event,
                    isset($cios[$user->nip][$dateString]) ? $cios[$user->nip][$dateString] : null,
                    isset($cts[$user->nip][$dateString]) ? $cts[$user->nip][$dateString] : null,
                    isset($dls[$user->nip][$dateString]) ? $dls[$user->nip][$dateString] : null,
                    isset($shifts[$user->nip][$dateString]) ? $shifts[$user->nip][$dateString] : null,
                    isset($shifts[$user->nip][$yesterdayString]) ? $shifts[$user->nip][$yesterdayString] : null
                );
                $details[$single_data['type_id']]['ec'] += $single_data['ec'];
                $details[$single_data['type_id']]['hadir'] += $single_data['hadir'];
                $details[$single_data['type_id']]['tke'] += $single_data['tke'];
            }

            $report[] = [
                'name' => strtoupper($user->nama),
                'nip' => $user->nip,
                'type' => strtoupper($user->jenis),
                'details' => $details,
            ];
        }

        return $report;
    }

    private function calculate_event_per_user($user, $date, $holiday, $event, $user_cio, $user_ct, $user_dl, $user_shift, $user_y_shift)
    {
        $type_id = strval($event->id_jenis_absen);
        $ec = 0;
        $tke = 0;
        $hadir = 0;

        $schedule_type = $this->decide_schedule_type($user, $date, $holiday, $event, $user_shift, $user_y_shift);

        if (substr($schedule_type, 0, 1) == 'L' || $user_ct || $user_dl) goto result;

        if ($schedule_type == 'EV') {
            $ec = 1;
            if (!$user_cio) {
                $tke = 1;
            }

            $in_time = $user_cio->finger_time_in ? strtotime($user_cio->finger_time_in) : null;
            $ev_end_time = strtotime($event->jam_berakhir);
            if (!$in_time || $in_time > $ev_end_time) {
                $tke = 1;
                goto result;
            }
            $hadir = 1;
        }

        result:
        return [
            'type_id' => $type_id,
            'ec' => $ec,
            'tke' => $tke,
            'hadir' => $hadir,
        ];
    }

    function generate_schedules($datePeriod, $users, $events, $n_holidays, $shifts)
    {
        $schedules = [];
        foreach ($users as $user) {
            $texts = [];
            $bgcolors = [];
            foreach ($datePeriod as $date) {
                $yesterdayString = (clone $date)->modify('-1 day')->format('Y-m-d');
                $dateString = $date->format('Y-m-d');
                $single_data = $this->calculate_user_schedule(
                    $user,
                    $date,
                    isset($n_holidays[$dateString]) ? $n_holidays[$dateString] : null,
                    isset($events[$dateString]) ? $events[$dateString] : null,
                    isset($shifts[$user->nip][$dateString]) ? $shifts[$user->nip][$dateString] : null,
                    isset($shifts[$user->nip][$yesterdayString]) ? $shifts[$user->nip][$yesterdayString] : null
                );
                $texts[] = $single_data['text'];
                $bgcolors[] = $single_data['bgcolor'];
            }

            $schedules[] = [
                'name' => strtoupper($user->nama),
                'nip' => $user->nip,
                'type' => strtoupper($user->jenis),
                'texts' => $texts,
                'bgcolors' => $bgcolors
            ];
        }

        return $schedules;
        return [];
    }

    private function calculate_user_schedule($user, $date, $holiday, $event, $user_shift, $user_y_shift)
    {
        $text = '-';
        $bgcolor = null;
        $schedule_type = $this->decide_schedule_type($user, $date, $holiday, $event, $user_shift, $user_y_shift);
        if (substr($schedule_type, 0, 1) == 'L') {
            $bgcolor = '#aaa';
            $text = $schedule_type == 'LN' ? 'LN' : 'L';
            goto result;
        }

        if ($schedule_type == 'SH') {
            $text = '<font color="green">' . $user_shift->jadwal_masuk . '</font><hr><font color="green">' . $user_shift->jadwal_pulang . '</font>';
            goto result;
        }

        $id_days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $tp = $user->{$id_days[intval($date->format('w'))]};

        if (($schedule_type == 'EV' && $tp) || $schedule_type == 'TP') {
            $tp_s = unserialize($tp);
            $text = '<font color="green">' . $tp_s[0] . ':00</font><hr><font color="green">' . $tp_s[1] . ':00</font>';
        } else if ($schedule_type == 'EV' && !$tp) {
            $text = '<font color="green">' . $event->jadwal_mulai . '</font><hr><font color="green">' . $event->berakhir  . '</font>';
        }

        result:
        return [
            'text' => $text,
            'bgcolor' => $bgcolor
        ];
    }
}
