<?php
if(!defined('_TUBEWEB_')) exit;

?>
  <!-- Mypage Area Start -->
  <div id="mypage" class="pd_12">

      <!-- Mem_info Area Start -->
 	  <? include_once(TB_THEME_PATH.'/aside_mem_info.skin.php'); ?>
      <!-- Mem_info Area End -->

      <!-- My_box Area Start -->
      <div class="my_box">
        <div class="container1">
	 	  <? include_once(TB_THEME_PATH.'/aside_my.skin.php'); ?>
          <!-- Right_box Area Start -->
          <div class="right_box">
            <!-- Content_box Area Start -->
            <div class="content_box">
              <!-- Sub_title01 Area Start -->
              <div class="sub_title01"><h3><?=$tb['title']?></h3></div><!-- Sub_title01 Area End -->
				<form name="fleaveform" id="fleaveform" method="post" action="<?php echo $form_action_url; ?>" onsubmit="return fleaveform_submit(this);" autocomplete="off">
					<div id="withdrawal">
					  <div class="text_box">
						<p>
						  '계정 탈퇴'는 구구 계정을 영구히 삭제하는 것이고, '이용권 해지'는 이용권 정기 결제를<br>
						  중지하는 거에요. 또한 이용권 종류와 상태에 따라서, 계정을 탈퇴해도 정기 결제는 계속해서<br>
						  진행될 수도 있어요. 따라서 정기 결제를 원치 않으신다면, 계정 탈퇴가 아닌 이용권 해지<br>
						  (정기 결제 중지)를 하셔야해요. 계정 탈퇴를 원하신다면, 아래 주의 사항을 읽어보시고 하단의<br>
						  [탈퇴하기] 링크를 눌러주세요.<br>
						  &lt;탈퇴 시 주의사항&gt;<br>
						  - 구구와 구구피디아는 연동되어 있어, 구구 탈퇴 시 구구피디아 계정도 동시에 삭제됩니다.<br>
						  따라서 모든 후기, 내구독, 찜목록, 구매내역, 프로필정보 등이 소멸되어요.<br>
						  - 탈퇴 시 계정 정보는 완전히 소멸되며 복구가 불가해요.<br>
						  - 탈퇴 시 계정 정보의 삭제로 인해 향후의 환불 진행,<br>
						  결제 이력 조회 등이 불가할 수 있어요.
						</p>
					  </div>
					  <div class="point_box">
						<p>보유 포인트 <span><?php echo number_format($member['point']); ?></span>원</p>
					  </div>
					  <label for="agree4">
						<input type="checkbox" name="agree4" id="agree4">
						<span>유의사항을 모두 확인하였으며, <u>회원탈퇴 시 쿠폰, 포인트, 상품권 소멸</u>에 동의합니다.</span>
					  </label>
					  <div class="btn_box">
						<button>회원 탈퇴하기</button>
					  </div>
					</div>
			   </form>
            </div><!-- Content_box Area End -->
          </div><!-- Right_box Area End -->

        </div>
      </div><!-- My_box Area End -->

  </div><!-- Mypage Area End -->

<script>
function fleaveform_submit(f) {
	if(!$('input:checkbox[name=agree4]').is(':checked')){
		alert("유의사항의 동의 해주세요.");
		return false;
	}
	if(confirm("정말 회원탈퇴 하시겠습니까?") == false){
		return false;
	}

    return true;
}

function showDiv( id ) {
    document.all.other.style.visibility = 'hidden';
    document.all.other.value = '';
    document.all[ id ].style.visibility = 'visible';
    document.all[ id ].focus();
}
</script>
