<?php
/**
 * Language file for relgroups administration
 * @license GNU GPLv3 http://opensource.org/licenses/gpl-3.0.html
 * @package Kinokpk.com releaser
 * @author ZonD80 <admin@kinokpk.com>
 * @copyright (C) 2008-now, ZonD80, Germany, TorrentsBook.com
 * @link http://dev.kinokpk.com
 */

$tracker_lang['rg_title'] = '����������������� �����-�����';
$tracker_lang['to_rgadmin'] = ' | <a href="rgadmin.php">� ����������������� ����� �����</a>';
$tracker_lang['relgroupsadd'] = ' | <a href="rgadmin.php?a=add">��������</a>';
$tracker_lang['spec'] = '�������������';
$tracker_lang['no_relgroups'] = '��� ����� ����� <a href="rgadmin.php?a=add">��������</a>';
$tracker_lang['owners'] = '���������';
$tracker_lang['amount'] = '���������� ������, �����. ��� ��������';
$tracker_lang['only_invites'] = '�������� ����������� <b>������</b> �� �����������';
$tracker_lang['members'] = '�����';
$tracker_lang['private'] = '��������� (������ ��������)';
$tracker_lang['nonfree'] = '������� (�� ���������� � ������ ���� ���-�� ���������)';
$tracker_lang['page_pay'] = '�������� ������<br/><small>(���� �����, �� "������" - �����)<br/>���� �������� ���������, ������ ������������� ���������� �������</small>';
$tracker_lang['subscribe_length'] = '������ �������� (0 - ����������)';
$tracker_lang['users'] = '���-�� �����������';
$tracker_lang['actions'] = '��������';
$tracker_lang['descr'] = '��������';
$tracker_lang['delete_all_users'] = '������� ���� �����������';
$tracker_lang['are_you_sure'] = '�� �������?';
$tracker_lang['view_users'] = '���������� �����������';
$tracker_lang['add_group'] = '���������� ������';
$tracker_lang['edit_group'] = '�������������� ������';
$tracker_lang['continue'] = '����������';
$tracker_lang['rg_faq'] = '�����: � ���� �������� ����������� ������ ��� ������������� URL ��������, � ����� ��������� � ����� ����������� ID ��������������� ������������� <b>����� �������, ��� ��������</b>. � ���� "�������� ������" ����������� ������ ��� ������������� ���� � �������� ������<br/>
��� �������� ������������ ��� ������� �������� � ������ ����������� ����� ��������� SQL-������:<br/>
<pre>
INSERT INTO rg_subscribes (userid,rgid,valid_until) VALUES (ID_������������,ID_����� ������,UNIX_�����+�����_��������*86400);
</pre>';
$tracker_lang['group_added'] = '������ ������� ���������. ������ �� ��������� � �� ��������';
$tracker_lang['group_error'] = '��������� ������ � ��������� ��� �������';
$tracker_lang['no_value'] = '�� ������� ���� �� ������������ �������� �����';
$tracker_lang['group_edited'] = '������ ������� ���������������. ������ �� ��������� � �� ��������';
$tracker_lang['unknown_action'] = '����������� ��������';
$tracker_lang['users_deleted'] = '��� ���������� ������ ������� �������';
$tracker_lang['subscribe_until'] = '�������� ��';
$tracker_lang['in_time'] = ', �������� ����� ';
$tracker_lang['no_users'] = '� ���� �����-������ ��� �����������';
$tracker_lang['delete_user_ok'] = '������������ ������ �� ����������� ������';
$tracker_lang['notify_send'] = '���������� �����������';
$tracker_lang['notify_subject'] = '������ �������� �����-������';
$tracker_lang['delete_with_notify'] = '������� � ������������ ������������';
$tracker_lang['delete_notify'] = '��������� ������������!<br/>��������������� ������(�����) ���� ���������� ���� �������� �� ������ ������ "%s"';
$tracker_lang['comma_separated'] = 'ID �������������, ����� �������, <b>��� ��������</b>';
$tracker_lang['relgroup_deleted'] = '����� ������ �������, ������ �� ��������� � ������ ����������������� ����� �����';
?>