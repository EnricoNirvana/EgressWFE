<?  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    
function _writelog($tag,$msg) {
	$CI = get_instance();
	$backtrace = debug_backtrace();
	$caller = $backtrace[1]['function'];
	$options = $CI->options;
	
	$timestampformat = 'DATE_RFC822';
	$time = time();
	$timestamp = standard_date($timestampformat, $time);

	$logentry = sprintf("%s: [%-10s (%+20s)] - %s\n\r",$timestamp,$tag,$caller,$msg);
	
	if($options['loglevel'] == 'ALL'):
		return(_logfilter($logentry));
	elseif($options['loglevel'] == 'POLICY' && ($tag == 'POLICY' || $tag == "ERROR")):
		return(_logfilter($logentry));
	elseif($options['loglevel'] == 'DEBUG' && ($tag == 'DEBUG' || $tag == 'POLICY' || $tag == 'ERROR')):
		return(_logfilter($logentry));
	elseif($options['loglevel'] == 'WARN' && ($tag == 'WARN' || $tag == 'DEBUG' || $tag == 'POLICY' || $tag == 'ERROR')):
		return(_logfilter($logentry));
	elseif($options['loglevel'] == 'INFO' && ($tag == 'INFO' || $tag == 'WARN' || $tag == 'DEBUG' || $tag == 'POLICY' || $tag == 'ERROR')):
		return(_logfilter($logentry));
	else:
		return(TRUE);
	endif;	
}

function _getUserProfileThumbByID($uuid)
{
	$CI = get_session();
	
	$sql = "SELECT profileImgThumb FROM accountreqs WHERE uuid = ?";
	$sqlargs = array($uuid);
	$query = $CI->db->query($sql,$sqlargs);
	$imgdata = $query->row_array();
	
	if(count($imgdata) > 0):
	    $img = $imgdata['profileImgThumb'];	
	else:
	    $img = "iVBORw0KGgoAAAANSUhEUgAAAG4AAABaCAIAAADW0vQXAAAJ8WlDQ1BpY2MAAHjanVJnWJOHFj7f92WHMJIQhoyPvQxTIIDICCsCMkSWC0gChBFiSAC3IqICdaAighOtiliwWgGpE1EcFAEV3A1SBJRarOJC5f7ofW6992nv7e37633ec85zznnPAaB3hUmkOagmQLZUIY8O9sfjExJxciegQAUSOAEkC3Nl4bODYgAABIF8PDc62B/+DW96AQEAuMUNicRx+P+gLpTJFQDIVABwEYlzhQBIEgBk5StkCgCkAwDYKZkyBQCiAgC2PD4hEQB5DQDstPiERACUAgDslN+4AQCwRdlSEQDqCAAyUbZIBIDuA4D1eUqxCACzBoDiLGW2BAA7DgBseUw0HwBrA6CopX3BU77gCnGBAgCAnyNbLJekpStwa6EN7uThwcNDxPlZYoWCG5kszEyWi3B+TrYsWboY4Ld9AACAmRsd7I8LAvluTh5ublxne6cvTPivwb+I+ITEf3r/KgoQAEA4Hb9rf5SXUwPAGwfANv+upewEaFoLoHv/d818H4BGEUDjzS/24QgC+Xi6QiHzdHDIz8+3l4iF9sL0L/r8z4S/gC/62QsC+fi/7MEDxKnJyiwFHh3sjwtzsnKUcjxXliwU49z/fNC/XfjHc0yNFqeK5WKpUIzHSsT5Emkazs+RiiQKSY4Ul0j/7Ih/s+zPbhdGjYVIAIgzHhIq5Xm/RQkAAESggQawQQ+MwAysgQvO4A5e4AuBMBMiIAYSYAEIIR2yQQ75sAxWQzGUwmbYDlWwFw5CLdTDCWiCM3ARrsAN6II78ABUMAjPYQzewASCIGSEgbAQPcQYsUDsEGeEh8xAApEwJBpJQJKQNESKKJFlyBqkFClHqpD9SC3yLXIauYhcQ7qRe0g/MoL8inxAMVQNZaOGqCXqgPJQPzQUjUHno2noInQJWoRuRCvRGvQY2oheRG+gd1AV+hwdxwCjYxzMBONiPIyPRWCJWComx1ZgJVgFVoPVYy1YO3YLU2Gj2HsCicAi4AQuwYsQQphDEBIWEVYQyghVhCOERkIb4RahnzBG+ExkEA2IdkRPooAYT0wj5hOLiRXEQ8RTxMvEO8RB4hsSicQhWZHcSSGkBFIGaSmpjLSb1EC6QOomDZDGyWSyHtmO7E2OICeTFeRi8k7yMfJ5cg95kPyOQqcYU5wpQZREipRSSKmgHKWco/RQhigTVE2qBdWTGkEVURdTN1EPUluoN6mD1AmaFs2K5k2LoWXQVtMqafW0y7SHtFd0Ot2U7kGPokvoq+iV9OP0q/R++ns1ppqtGl9tnppSbaPaYbULavfUXjEYDEuGLyORoWBsZNQyLjEeM96ps9Tt1QXqIvWV6tXqjeo96i80qBoWGn4aCzSWaFRonNS4qTGqSdW01ORrJmuu0KzWPK3ZpzmuxdJy0orQytYq0zqqdU1rmElmWjIDmSJmEfMA8xJzgIWxzFh8lpC1hnWQdZk1yCaxrdgCdga7lP0Nu5M9ps3UnqYdq12gXa19VlvFwTiWHAEni7OJc4LTy/mgY6jjpyPW2aBTr9Oj81Z3iq6vrli3RLdB947uBz1cL1AvU2+LXpPeI32Cvq1+lH6+/h79y/qjU9hTvKYIp5RMOTHlvgFqYGsQbbDU4IBBh8G4oZFhsKHMcKfhJcNRI46Rr1GG0Tajc0YjxizjGcYS423G542f4dq4H56FV+Jt+JiJgUmIidJkv0mnyYSplekc00LTBtNHZjQznlmq2TazVrMxc2PzcPNl5nXm9y2oFjyLdIsdFu0Wby2tLOMs11k2WQ5b6VoJrJZY1Vk9tGZY+1gvsq6xvm1DsuHZZNrstumyRW1dbdNtq21v2qF2bnYSu9123VOJUz2mSqfWTO3jqnH9uHncOm6/Pcc+zL7Qvsn+hYO5Q6LDFod2h8+Oro5ZjgcdHzgxnWY6FTq1OP3qbOssdK52vu3CcAlyWenS7PJymt008bQ90+66slzDXde5trp+cnN3k7vVu424m7snue9y7+OxeZG8Mt5VD6KHv8dKjzMe7z3dPBWeJzx/8eJ6ZXod9RqebjVdPP3g9AFvU+9k7/3eqhn4jKQZ+2aofEx8kn1qfJ74mvmKfA/5DvnZ+GX4HfN74e/oL/c/5f+W78lfzr8QgAUEB5QEdAYyA+cEVgU+DjINSguqCxoLdg1eGnwhhBgSGrIlpE9gKBAKagVjM91nLp/ZFqoWOju0KvRJmG2YPKwlHA2fGb41/OEsi1nSWU0RECGI2BrxKNIqclHk91GkqMio6qin0U7Ry6LbZ7NmL5x9dPabGP+YTTEP5ljPUc5pjdWInRdbG/s2LiCuPE4V7xC/PP5Ggn6CJKE5kZwYm3gocXxu4Nztcwfnuc4rntc732p+wfxrC/QXZC04u1BjYfLCk0nEpLiko0kfkyOSa5LHUwQpu1LGhHzhDuFzka9om2hE7C0uFw+leqeWpw6neadtTRtJ90mvSB+V8CVVkpcZIRl7M95mRmQezpzMistqyKZkJ2WfljKlmdK2HKOcgpxumZ2sWKZa5Llo+6Ixeaj8UC6SOz+3WcFWyBQdSmvlWmV/3oy86rx3+bH5Jwu0CqQFHYttF29YPLQkaMnXSwlLhUtbl5ksW72sf7nf8v0rkBUpK1pXmq0sWjm4KnjVkdW01Zmrfyh0LCwvfL0mbk1LkWHRqqKBtcFr64rVi+XFfeu81u1dT1gvWd+5wWXDzg2fS0Ql10sdSytKP5YJy65/5fRV5VeTG1M3dm5y27RnM2mzdHPvFp8tR8q1ypeUD2wN39q4Dd9Wsu319oXbr1VMq9i7g7ZDuUNVGVbZvNN85+adH6vSq+5U+1c37DLYtWHX292i3T17fPfU7zXcW7r3wz7Jvrv7g/c31ljWVBwgHcg78PRg7MH2r3lf1x7SP1R66NNh6WHVkegjbbXutbVHDY5uqkPrlHUjx+Yd6/om4Jvmem79/gZOQ+lxOK48/uzbpG97T4SeaD3JO1n/ncV3u06xTpU0Io2LG8ea0ptUzQnN3adnnm5t8Wo59b3994fPmJypPqt9dtM52rmic5Pnl5wfvyC7MHox7eJA68LWB5fiL91ui2rrvBx6+eqVoCuX2v3az1/1vnrmmue109d515tuuN1o7HDtOPWD6w+nOt06G2+632zu8uhq6Z7efa7Hp+firYBbV24Lbt+4M+tOd++c3rt98/pUd0V3h+9l3Xt5P+/+xINVD4kPSx5pPqp4bPC45kebHxtUbqqz/QH9HU9mP3kwIBx4/lPuTx8Hi54ynlYMGQ/VDjsPnxkJGul6NvfZ4HPZ84nR4p+1ft71wvrFd7/4/tIxFj82+FL+cvLXsld6rw6/nva6dTxy/PGb7DcTb0ve6b078p73vv1D3IehifyP5I+Vn2w+tXwO/fxwMnty8h9s8eNo+HidIgAAAAlwSFlzAAAASAAAAEgARslrPgAAAAl2cEFnAAAAbgAAAFoAZ3hm/QAALV1JREFUeNqFfWmUXVd15t77nDu8qapUqtI8WJIlS7Ysg20MthNiBmMDAdKGAB3oJDQNDpDQCb3SSXdnda/udBIgC0ivxCGBQKcTrxAIg5kcwGAT4iHBeJRlW5ZlzVOppldvvMPZu3/c6dz7Xpm3akmv3rv33H32/vZ49jmFdx65GRAAAATSN/lLyr+i9Qli8enojWOGkOK3VS9OvsPsSQI/5YUgCCCA1pWS30vj7siHtb8d+2FOKBfcERwZJ2cK0piJ2ZchFrOzJ2tzB0fuLfgg1m35/5URRwkQkMoFWP5EKqMWj0QBFEvSI1Mbebf6a0ScpedjeRzRZbDl91ikCGaXWHwp0YSlh4lY39hTSsRmP6tCDZbelCDMVe6k30p2QT4LGT928eFqIsTS4AVcZPVrS0PpdMJSvkEAUNKvMCM6YWv+bw7yhNcFLixGSz5VqlAxbqLZDFCyR0DxZjWAVIeidJgEOAjp3MRSCKlo6FgVGctEGcPE7CpdUGaDQ8r351LCjMs50TZPx76kDJASLsbdKDbjxBIMrn5x+ZXYzwzFFkiw8v+4+3OUvMhMxr/0mItzZiGUgDlKR46aiqDQ0nEQC7bFYyQ3aAXGcUTXK0pdtiQAFeYiACfDYqIOUv6exzNOVhsw+xZLlFcvy4bUBdHjRYGFauTmEi2rlLCgpCPlxyTuQnI/q4QNICgHOcIMpDkGy/yCETUvi6MkMwQBVA6YmIEVko2GF51aCaRj+VhRiPEqSNZXmDpKLA8HMAIWGTe0AHAWOozCJ/2FY3bq5CpaPh8awwAIyKWpvphXQMD8p/p8BDIxL5+LHKWcOrJhS1tX082fFkuM+WZVU0bjvrYVTTJSGJBHgricjaMPSEikbP4AjGyktdaZO2ruv2ueDTouZVFF5g0kcRKWqCSHRiKqceYSUpV2XGKDD3x1Ye5o2Jp22ZhUslU85/9LFrpVzLcASEYJWtNZ5SUAgtrifR7uImAe6L6IGbbkVjKFJQ1N8MMGSEOz6d37+fOnjqy8/vZtU+tVGAhiQiIXBEA5iqpqX1nXpIiQATE2MrvNcb01//gXJ7bsbr3qPRv63ZBjIRpxd4JVUqVi4ixnXbX1ZSbmlN75/OugsBky4iXGWcAqIyuBFBUfIyCgicWtk0T0f//T4Sjm2/90HygTDQySKlmMPAJLRDAm9IdqlpKab0IAQBQB5titKTDOZ37jae3gez6xF7UJ+4Y0Fl5VIFOXjMpknDE5DBQ2sPRtedYCAFhJlbLAsOAUgiQ/lP4ApXQUgKTST9mlG2O8Jq7M8R+//YnFs+EH/mw/QxwOBBUBcmZex0VwYtOdP8XW/sKG5HaHFIUDYYh+7Y7LF89Gf/yLj6/Mgdcijm0+5qNwZrsydBWD5zMSy6xhIcuqwZBKKjo6Hxt9kFm0ip8d9xJEAI7Fa+iF4+ZP3v3k0tnw9k9fzhSaEEjZvCjrMmayzG2M5A+1bsoicEBM8ZIGCUgK4xAYo9v//PKlc9Gn3n1w4Zh4LWKT35I/LsdXxqzcoeUPLSmGZRbQCqQsfalku6XfEptejFea/oiLt4cx4Naxfcb85QefOff88O3/Y9f0Fh30GFXOlxwaFYHZyZ+Uf7WuFSkGQQbk9BNBIgj6PL2V3v7fd59/vv+XH3ymfZqcOoixTFkJ9SN4GnUPSeRQIcISDGVxX1IvyC1IQW9Gq+3NZcRSJBDOFZaBGR2O+uqvf/u500/3rnrd7NW3TvfaQ9JJNsmpUU5MRxkq1q8Zj/L4wUJIlQ9WWQABSVOvHV3z+smX3Dxz6unuX//2c6brkJNnkTAykTyFo8IDY0XMo+kmZukpUMpp25LKGOpKUYIVBFnissQnYMR4fu2rHzv+/CODetO94a0z5HPqkUqiwpE3VGJlFY8vmqTmiEJAEGAiT65/2/pG0z36SOcrHz/m1WrMBgUBKa082NCuzLgUjdq2NQ+nSmSRNVxByXiNLf3Y88qekX1sGBsT3mPfXnzoK3N1X6/d6e69YTLsGcw9dineghF0jA0hE9tUcaNjX2kYhyRBz+y9YWJmp1vznYe+OvfYtxYbE75hsFTNvi+30ZV4axz4i0QoJYayAh9nWcfoBLAwt2I7dMzMaGl6IkyO9BfhW3923HO9YBjvvX6yMa3iOKd0xNWsJvYxgCsrWsmbZ0LNvC0imhia03TZ9VPBMPYc95t3nOgvCjmWfR4jlCx/TeYIeRZQflBp4pAHQyOZQMmf5Oa2jMqyUudvmKFe9x78yoXzR4ZejYTMzqumRMyIG15tJiO0wCrsLWgZI6OM8cgiO66aAmKvRheeGz745blG3eXU0uKYAXMrPAaLLxa35IYpe1MEH2jdmzsfKae0I9wU0Q62582DX57zfDeOjF9Xs9tdEwniCGljnFg2AxkZe1w2UI4EYMx3KCbiddu1V1dxJL7vPviVufa8UU4lOLUyFBgn8VKytCoryzpVuAGxyB+dMJSxnP4wi1d3Dj+4MvfC0PGUMeLUyJ9AZosaGcu+siWB3EiNAMf2UeMzYykCCQRh9ieU65MxrH2aO9Y//MCKV9PMY91xnjdDiYYq3+2npYslY7OdCr/EspIZCuw6W+XFeOifFlEIUFjQ9chxFXPZbxf3ji0WiPWbnclJuW4iPxUpAMIM2iXHB2EEEGA69E+LmY+vcAQs41iZHI68qTwH7dJvNkABFywDVqx1ntGpptrdmY+PPdF1fBIGAohDiGMmxDgVSFJH5DK0K0GP5UnRQmgpbK6sdI7MMC/FIBhjTEiIRpgdj44/0V2ZN14T2VjVhjz9l1F67BFHkvSMKrKus0EhZW9uJaF2QbNwbQIgIqAdtXAmWJmLtFYiQgThQIKuIcqXWCpB2UjAP9az5e7FNrg4QnbpdkzibVI47HAwMEgoAo6m9sVw4UygXcowPuLgUMr5SF6H5XJNtsRpu2KYvcGKgbBmk67wUW4gKvNSDi6eNtGAEVFAiGjYDxdOx8rBaknzxVWzYK+UFMU23KNSGBlERJSmxdPxsB8REYCgonAgi6djpZXYZco8jxxDWJH2jyZcuTAIityRirARRn/s27hccULMykWIaulcnw0DCgoCgYnx5FM9IioNUJjdcr5YKiJgdgEBSoEFQduoWOwlq59AMuQLEZ081DUxUoprEealc/1E2OO0wS502aG0FV2MyTjTGChJWy3LWoqEKty0AqMUL2lRRQQAIRgwMKVrVSKu1s8+tBx0hRQkTEe7KFBgFUuiSZWAxjwabRORlegxZ39+GQMyEgc9fvbBtqu1ZEGyGAwGphpjV+Zn86Ggalyum73I8jaWHcXVtFHGfFgxgpA6RwEQBreuzjzTOf7EwKs5wmBlqeUsILcz1RDeMvAj2b/1JZbpwyRZcOvO8ccHp5/peHUSMxK9Vl5VHo2aj7HWBPPEMQ/BshrMi91AVu3TDmuzZR9hr05AXAgPIQ7oR188S+CIZAsmRdGFivV0yOPZhB4DwCAMIsAITKX52z5HMgSAFPUtIBBU6Pzz358zQwLENOUWQWK/TlaRLS9mWwtZVf8zlo9iqyxVhTTeG0jGOxhzXSZDBBCRqfU+KhDhxKgJc62hD96zfPhf2rUJzWyZQ4RSqIACAsLAhtlwEkIjCSognRkuAWZgBkmT6DQOL8oTKbHChmsT+vC/tJ/8/lKtqdlwpkGICic31ERWm+lohWU1PpY+pHEfjuNrdXkgw4WdUSGYWNZu1Z6vmbNpJaEh411/fDxYAeUACxWkpCYchZGNILFXp+aU25hya3WHUHPkBF0IuiixJtB+QzennOaU49UJidkIS2YDi24REEZyIFzBr33sOBjEpKKIggDM7Nb02i2juayFqZJTQStuyy4bJwZdXayoGqk8AsijBBuPuWlDAEGUOOSZLf7EOqd91igXkTGxmH7dOXFw8LWPnnjXH+3qrQySJSARQBCOAbSpNV3S1Fvks88Gpw/3zh8ZLJ0NVhbiYGDigAFAe+TW1ORavWaTt/HS2ubLGut3uY01ig0MB6HEmCxySAJtkGbdv/O/HD15sN+c8jnm1BkQmVAmN6uZLX4cGcwbjArEoKXpedOhzRBIFajCirT9KgkYR4sfkkMvU0rMP5VMOGIVvsDE3FrrbjvQeOTYUsPX2XILsImbE/79/zA3ucF7029t7iwHJIiIoLjedMMBHH6w89S9y4f/dWXhZBAOGACVItJIxIgaAEQCMHjaiDEiwF5NzWzz91zX3P+aqUuvmXSmYNAPxChkZpDWZP2bf3L6gX+Ya07W2cSIKbIQJQzMtv0TrbWq14kVIWDmYcUuX2IenFhwlGr6V1Z3nath1SLkFqPQAqsPq9DxEkiFQYivumn6J9+8CODmJg2AhE2z5X37T0+jwBt/c2u/NxDDELkP/+PiP3/p/Ikn+3GAXo1cT7secWziQOLIxIwAkYgAIhFrrb0aKOUK8MLx4L5nhj/64sVLrqz/7Ds27H/tGsAQFDYb/rc/derbf3aq2fJE4nLMTUDhgVdNCxoAERwJvAtzm0WHkttxG2R2MpZyD+98/uYyBrG4NBVFGYnp8ypWJqtzCCABD9UnfunQ4qnYcZPIOgmPBBEQqNsNfuYXZ9/63y4hpe54/5OP3dOemqz5LYUiw74EoXEdaM7oNZu8qXVeY4rcugKAsG96S7w8Fy6dDboLURSI62u/TgAw6Mpyu/+Smyd+/bNXcixf/oMXHvjSXLNVEwGrKxEQJQxh7Vb1kb/bT34MnPNiFXeam3K0rVzugTFjcfoIXeKPPWBhDexQwKq85fgvG0+OoDlD198289WPnfJ8j7mIqAVAgBsT7v1fnD/1zOBt//WSf/e/9s1uO/Hw1+fb87Hrq817a3tvnNxzXWvdznprLTmeQoWY+GghNhIFZmXBzL0wOPLjzrP3t88eHgQD47fwlvduvOV9204e7Hz5D0+eeKLXmKwJV9rYAImCQfCK2zY0Z6i7BKSgrGSVCVqMgworLCchedEHsOhFH8NLHG8Xqq8yHQKgwPTVJ37p4PJJo32StGVXJAO+UjLsMjrmpl/e/Jr3bO4uRv/yjXM7DrT23DBRbyEzxVEcxyKcxKGUiA4RCYEc1I5WBP2Oee7B9rEnO69486bGWnXv58/98G/OSYxeQ7EBGwtJNTEc8vRW9ZEvXKlqMZicckzXy1atCYyw2F4CsmF05/OvK9+WQzSPLCyZFF2RsPrj0RhuTPiPfGPh//6n55stz4gBoXSATE9QATP220FjVt341g3Xv3V2496aCIeDOI6ZTWowsuYDAkBO0hVkVOA45NQ0Ap0/PHzoKxcf/PKFzrxpTjiIkNRGS4kkskbd7QTv+cSua968ZrASIumUFwX00EpPKqlzVSFLFdWsXD3KykxWwOWGAztrG4fWnAYBYIxZmk3vb//r8w9+cWFqpmZMDIWRz9GCqMGEcb/DTp12XNXY9zMTu66eWLfDq69xHE8AYNCNclbWGhpQokD6y+HcC9HRRzvP3N8+/ng/HHB9QmlNxkgl4xUAQCalV+aHN7xj5t1/dGmvM1CEQFSqvKb4yFhpF71G4SJQrQMken7nkVvKTMxZnmVFpVZzKdUr06BBRFBEAIWIlAbSRITKIQn1He8/9NwDXa+mkxRYbMozQZMCNjIcmDhmz1eT65y127w1692Nu2s3vGMWFQMAx/rBL50/d2S4fD5cODVcmYuDgXEc5fqKNDKzSJb8VyRGEA7NnhuaH/rMFehGHAmzmFhMDGxMajYSE2IrXH5/sZaFabebjUpLcrrsdCyop7wvxRKpTqfcRBYWBkJwfHI8LSxBl9vn4vbF/vJc3F1kYV67vqF0V8pdDmmAgWkR0xgBAL+hEVGMdObM8tnusM9rtqvr/s2MW0cBMaH5wf87t3Qi9upKa9KOcj0tIsKQtFahNTjaE2IkhdMbGvf97Vkiak7T5DpnctZrzaj6hIcIUWjiwBgGIkCklE12tRRKxGdvJAOF5Ky0+GVnS6UG7HwIFBAUFAYRcT1y6zrom7mjw+NPLD3/SOf8c4PlC8GgY+IARQyzKEfX6gqy1dJRk1CQxgnmRTno+MrxVWuNRiAAk6CjtcaNO6g0sgEAMMk6V1Z3LMVsdgohoBU99KULUWyQhFBrD2otNbXe2bi7vuua5o6XNGd2ejWfoqEJh5zUAS1L8VN9b3qBLlUAcQTeRQQrkkiMkYW9mnZcdeFY8OS9F5+6d+n0M/3BMiOQdlA56GjtugDooACImGy90fJaMmKE7NQKBGHQjZ1FTohBEBTqLoWDHjenNCKwSF7Tlyw3KcIVzOxU+ljxG6qGTlJCFYawK+eWgtMHh//61fnalNqyr7b/1VMHXrV2/S43Cng4iAkRCazqXzkYKvhT6LS67cM7q34KoKwhCUwRADgW5WG95Z15Nrz7jtN3ffTU499ZaJ9nReTVlOuT0phqmkASykimBWncUbXitnVGAECC2HDQiTfu9a+/bd0l1zYwMVIakaG7FC+cDRUhaRLOtSwfCwEAR2WUPEAkBb4AEmqHnBq5PkmM8yejQz9afvQ7iwunorUbvZltdRY2oSTtUONJTv/Li9OYxZWrlXpzehhFuDHlLZ2O7vncuR9/ba7fxlpDaScRdBo0Zilr2a0kb0s1kSzbEnvygoTR0HhT+MYPbXnZm2e8CRp2o8xlYq2phh34ydcvfuuOM+GyOD6xKWkSlsYvYCSZ8pemVXRRCyEiYhzBoBfVJvHlvzB783s3r9mie+0hokaSEf5Yvih7GN555GYY87JWIhGEhTR6nvvw1+e/8X9OLZ6MGi2XFGQFrtzQl9RWcupLERDYH9hGDhHiQJqzePunL99+wOu2h2xAqWzvnQAzo6KJSe/Yk8FnPnioe5G1iyAkyVKwpINUNSzndPlrSQusef6HSECEJpZ+J5re6rz5N7e+7C1rh0EkkaDK6xq5a7EDRwABddt/3JXAs9gZml+BCChswKkpHqgv/cGxb37qNISq1lIiWaUi82OJKhACJiJGRMwlYkGkjFfMAszEXhuJf+UTe/a8vNZeCIkQ7U0lycgCva7ZcImzbkfjkbsvUin7y/Vcsu14heYnoyWtgIhAqeKm9jArPaMwIIjfVEFHfnL3fPtiuO/la3VdTAhEVm3Njs/TrkrUZWW2KEAGAGPEb6jOOfyrjzzzwsO9iWlfWEzMSTyUxbloYuFYTGzYSA7TJKUhIqVROZioiZQbEPMmBULsd6IDt6654pVTK4sDrdGqHeQ6BYDoOLKyGOx/5dQVr5p+4juL9ZZjWDAr42YhsSSiTXJCE4uJjTEgBXmp1JVC5RBqQgRhEQEQNJEoB5ued//fXTz3XP+9n9rb2ghBz5DK4vrCWhS7u8ob86zSpiBIDLWGc/EF8+kPHVw8ZianXWM4GQQVCkMwMHFgSENtSk3N6MnZRn2N9hvgOGQiCAbSb0crF8OVi1FnIYxDdjzt1TRpEVMk5LnrEIa9108BMiYLjdVejKKshwiAfNn1U4/fvZAZyHyHKQuhQuCYgkEcBkY70JrRE+u8iVmvNqH8OipNcchBX7qLcXs+6MxHg2VjjDiudn1ClSx4CIhMTnsnHh/8n/cc/LU/v3x2pzvsRagJ05ityjadaZsdD4kAsRG/RktnzKc/eGjxpKlNOHFsEEEpiiMYrgwdX23aW9t93eQlL21u3FlrrdN+HZVDgEmkhyDAhruLJhzQynxw7MnOUz9cOPl4v7tkPN9xfJQ0T0MUEBFyaHqjw1y0PoqMzYQBEIzE05s0aS0ixYIBAimMAhgOIq+pdr2itv/nZnZe1WqudZ06tKYVaYSszgQiJuKgDysX43NHeyce6x3+8fK55wIzZK+hlUNiOI65Puksnoz+4gPP/sbnL5/cSMGQkQhQoFhWScnTeQhRCtBZtJaopz7/kacXToTNlhsbJkVipLMUTqxX1755wzVvnNl2Za02QcwcRWIiDkORYeFimLk56T7wlbnH7p7b9zOzl76s9Ssfu6w7Hz7yvflHvj0/fzzya1q7xEYQQBFKzBeOBVe+RgHEOWeywewlFwERRfrCCwEbUUSxEUBAojjioBPNXOJc/cbNV98y3VrrnXq69/j3F5+5f/4lb5i99dc2dpeGqDL7DIKEysWZ7Wr97slr3jDd72w69eTgkbsvPvH9xc6FqN7SpNBEXG/qhZPh5z9y+Nf/6grlhRJjurpnW54scUx+oSykIANRw6/d+fsvHHu0O7nGN4a1wn7HOA15zX9Y98p3bpzd5RkThQPTWTYIiASpaSIoVhABSVGtpl94JDj37Ll//NMzu66r/9bf7/s3B7be9O82PHzXwn1/c27lbORPuoQS9E1sTBTGbBjKml3IOXeJgsZAFBpj4mBATg2Fsd8OJzc7b/jwtuvesnZqox4OzKfe+fSRH/f8mhr2+Pq3aVJACilhZbGgJlEg4ZBBDGm49Dp/z43bX/vvN//TF84++OWLcZdqLYoNNCbcY4/2vvKxY+/+w129bp9AAXDWB5iSpW778KUVupmlPuE+cvfiNz91qjlRYzaI1F0O99zY+tWP77rh7etVnYe9kEMCQiLCbGuA5WtTDGlN/RU5eO9iveW4dd1fjq+8adZpsnJk3w1TV9083e2EJ57shb14Zpf77j/afd0vrA37sXbJGEEau3EVmUW7FA3iXddO7DgwcfJQZ+l0EMfystvW/srHdx94zZSBKI55/qj5wefO1ZpOraZQyyvftX5mqxtHiaG1lnESCFBilTAOIRzGtWk88OqZfa+YnDs1OPvcwPO1iHi+c+yJ9rpdje37G8EwJqpsBUuynZI9ElIQdOBvfveFcAWUC8wY9IPXfXDzu35/Z2sD9VdCZCCFiIJjSlr5YJjg0/PcR+6+OOyCo7HbCTftbey+thl0JRhG9Ul97Rtnmmv0zA7vlz+6e/Plvoljz3N6Kzwx7ZhYTIZQEREWFiEt9abbWzaNCSfmaMPu2rU/vy425rq3TP/Cb29XLvd7oRisT+jHv9t+7HsLvu+EIdfX0C3v26J8EbYS19RulFIlJEFCjiEchGu2uNf9/DoBOPzQEmkihWzozOHONW+YdVwp9h9mMs5ZmX7ALI0J95/+du7huxYaU04csoj5pT/YdfP7Ngz7wzhipVbbaV9d8kFEY2Ri2jn6aO/84b7jK44gGETXvHGG2ZBCiTkIeOfVrf03TbHEpCDq0tc+cfprHz/mOs7mPX5jytEeKke0o1xf+XUV9+mhL1/8m995/uLpePfVU9oTgPjAa6e37m/0u6EYIJXwSn3jk6faZ9nxKOjGl904cePb1wVBXJo9QtGUUZ4LIiBhHIgRc+Wrp6c3+QfvW0yadhZPB8217t4bJoNBlAWl6T3qtg/vKtggQpqGbfqH//2C6RGQxMb8yh/vftlbZ7oLARIm3WAlAI4y1JKzMDg1QlGPffei4yqt9dyJ3vYrpjbt88MhExEixiFH/djx1aEfdj/3G88dvn+ZQ/XkD5YO/mh54XTYX+TuAi+ciU48MfzxXfNf/9Sph748LwEde7T96PcWZrbW1++sDVdiE5kkoo8Z6hP62ft693zudK3mIUoYRa//ja2bLvOiAaNabWPeiFYJIAEKDvvxruta67bVHrtnEYEIaeFs95pbN2ifraYaBBBtZSLCjPWWc/D7i3NHw+aU12kP3vE/dl37ppn2fFcpXSSepTJyCu8yZwkEQIQIB714/ysnt+xvnH0m8JpCoL99x/Hd1+8nDWIQiRMUERKhLF0I6s2aoHFcffFI9L2nzpEC7SIARiGLQddTzZbDKHXPXz4/JBAiQAWYRHuCSkvQw2/ecYJEg+Jhz2zZ37jy56YG/YiUSut12TJFyRwVDYV2AiVKU+fi8No3TXcW5Yv/87nWRP3C0cEzDy5d++apfjsmVRQ0y73oyGTwqXuXiajXDm5424ab3r1uZbFHWltLYRWuWT+5eqS0IaBADG4Lbn7vZo4YGf2GOnGw/81Pnmw2feY4GZOIhv3omtdPv/V3L+mu9BEwFnF8bE25jZbruo7j6kbLbU05jo+xCInqrfRv+91LXvqG6UEvynWFhZtN71ufOH3yYM+vKxE0sdz83i1uCyQGQJOX/UtWUvLeiHF1cGB0VHtxcNO7Z69/24beSkCkn7xvAUySsxZRcNb0BSiitKb2YnzqUJdFZne6b/nIlv4gQKI8MF6FhRUDVHqLCvrd8Opbp69+81R3KQTEZsv74d+e//7nz0+trTGLCAoKkVpeHPzsOzfue9XUoBNpIhBgI8wimdMxLElTdr8b7L1pzSvfubG9MCBFSSzPLJNr/e9//sIP7zzbbPlC2FsKrn7T5NW3TvdXYlIlD1OhsZRWVxMCRGAi1e8P3/KR7bO7HBA481SvsxAmxRSblXkLnSiXFk7FKxcjZn7Nr25trVNxyJh07xZbB2SEj1lHbFGqyH8QAFFUGIW3/c6uDXv8YcegkkbNueujJ/7xL843Jj1SwDEAGmQUFb7h9q3kC+S9kElWXIAEwRB58Ib3bxUVIhMCsQFS0pj0v/vp83d97ESt7qGSYdds2OO/9Xd2hVGIpY5vKvr3CkxA+V8CIcC0YREFESUOzeQ6fM2vbmXmzsV44XSsHJWlv1llJJm2CCulFk4PuovxlsvrL7l1Tb8bKZVtL883vqK9bmlL0l7AKC1PImEcQnMG/v0nd9fXQNhldMnz3bs+evwL/+0FiZz6pBYWQBh24x3XNPbftKbfizHfMy6FVpCCQS+88uemd17bGHZjABCW+oTmyPm73zv6tY8e9zyXHAx6XF+D7/nk7sYMxGFW/7H3xAlknZ6QVu7F4umY6iSQUr1u9NJbpjfv8zuL8fzpQKmkRpdOlvIiLACiku5SNAz5pa+fbk4rE+WWcbXzPyqvTOzpkQb5eTpCCoNuvHmf//4/31ufpUE7Jkcak7X7/37pT9711MEfdPyG57cUIIjEL/v5WVFiWaGMCwnNCq590wyLQSR/Qvl1ffDezp+869ADX1hsTNbIlWE7aszQ7XdctnmfH3RjUpAtutgCHpkGWj6neFNcggAmksY0vvT1M0HIvaWQKCuVpqjMm0VFQLC/Evme7Lt+bRRFWYWOSwvgVTryRuFM0+1dAXkLr4jSqr8SX3J1/UOf2bfhMq+7EINIa41z4fnwsx868pe/duTQD3oErvZwx0ubM5t0HEoJHwKIGAcwvcnd+dKW9hBBH7q385cfOPLZDx65cCRoTjsg3J2P1u/1f/2zey+52h+shGn9IqMBBIqiTlK5tFcGS/gYRYoQYRTF+26Y9jzptyMAQWuy2hYLIsahTG9y1+1wozDEyl6zolxbeVDuCl9kZQ4FhDQN2vG63fo379z/tY+d+JevXlDi+C0tIofuW376Ryub9vgHXrvm1ts3bby0uXhqWXuucDEmIoSh2bS7VZ9U3/zk6YP3LJ89MhCGRssRwmE7NhBf/2/X3/aft7t101sxSlvhd2Hl7YgSLMpHjmeQvGCeLsYhQBzA7A53zSY3jtg67iitV2Ie8ROx48P01nptAvtdJLKaFypswjJgivDBsqMjSRECgIaoL+RGv/SHlxx47Zrv/PmpY493tdaNSQeRLjwXfuvpk6/4hY1bLm8+fs9iHaxlDQRAMHG8dd/Eypz51p+e8rVXb7kgMuyYyMQ7XtK45YOXHnj1xLAfBgNUqrT2aL24KNxaTQ6lfc2Y0W/NEQUBxRhutPTM1ob2cqQVlaHcJrMI1iec5oxWSoFwNerMBYhY0ncryK9cmss3+11AABWIwf5KeOWrm5e9fN8jdy8/8KULJw51TAC1muuQ6rV5Yp221+YLsQhOrNPdZeM5WhGsLA2VizsONG98+4aXvnHKrUlvJUDCrNMICstT/CoFlGxmjVlXzNZw8hZLABAgreszujGZLO4XU9bFJBHiWGa31U8/t5L40xKbsMKrimEWC9yQ1vKqi2ZWtIFCinptQxquf/uaq9+45oVHe0/du3Tk4ZXjT/d7y8N6kzBfgraSLCCptai3HA6DcPvlky9/2cyVr57cefWE14B+J+qvAOlVcsGyfox8OLIbqrKkl5d0UYTNxFqc2dZM6qQ2Kzlf6orDeO02XZ+qmSjrDCq1TFmMKxFlKUsqxkItskEycou90UwKRaDbjkjhnhvrl7+y2V+Wk4c6my9rPHJ3L4dFBmlJ6mG9drT3xqmP3HnFtssn6lMYMwf9qNsWQiJtmbz8sMMMKlYgufqrdE0+QiZPIQSOI6xP1Wa36jjktDEGAEC0BV2MI5iccdbtaAz7EWklAtaRJmWIpQy19tIglx4vUGhWkZaJBd3UYimlAGTYjUBIadx5baPWoPZcnMhAshXJHNTtCzy1XnvNpolNty1JwVnRSIQhliIXE7A1PStcFlMYNVAWTwUBBImG/Xj9JbWJWR1HYkPXqgMjgohyYMNOZ9iPUYmMUY9xJrGCTVntS3ulsdwPBkCEpFAYg76JhnzxxJCIRkMCQpw72Y8CEwyMGCSFaRMaYNUEVc4EqqhLBR0lzI4sJZWKuRT0ow27XHIB2D6BI2/rBwBgRIiNrN+uxGjhpLeFshVW25tDevjuqM2ueHI7ysVccoIIljFOZJ6WDrWD/WU4e6TrOEpYMF30R0BgEcdVZ4/0+23UHmVxkuUT0Jq7lDkysn5VyL44ZrkC20zquYECFBbt4ex2zTEQkmT2Cu1yRl6yVI7Wftrtk0y8GHQ0qVodoGmOUextK+KS7B8r/UAAEGZwfTr5TGfheOB4SuxlHgFg0B4tHA9OPt11fWIxIFweR0p5bU5KjtBSIcNObyqIsAewYYLC4HqoHZU0lSa6leXtJa3I5WydIpAjv6Kk9mtsxD5KmYyn1vqeSanHv7MYBWUznSEGCeJAHv/OAqlkMqNnl45WxUdLBqMfrLKyUog8S9vTAzYtBGQ8oyo6qm8k3UladG0kpQ17u5p9kJ5VWRF7IalyRJlNZ7o5kEU8n84dCR777nyt4SSL4Fg6pBOExW/ox747f/5I4PlaGLLNjzY3rY3hxUbyClayqAWzokHhpqXIMkdotYoghTYn15IV7o2cjVcBWuWE5JLyjlw8cpCeBRk76MMcrCLG9Zzv/9XZ3oKQlvwIgWSlIZuIaE29Rb7ns2dc1xPhUm3fRkJxWAQWIKrSJCNvMxNheSGsGIexBiQNi8ZWTMYg3rKeJbaOGNBS9DM6jvUWARAIMDamOeU/8b32w1+fb0xo5oR7jAqigKOQQaEAoyAzN1rOj79x8Yl7lpqTXhwzymqn5GTHDIwWWG06itTBrl0WZw0U9mLsU7B4WH792MzADgxtj2mNWjprMBOgwDjTAdaAkB9CYGLxm3r+hfBL//uoJie5VQSUowadeGaHP3OJN+wYpUkwsfbokPul3z968VhYa5AxxYkT2aFoZdnn5JUKVzasSsltprzW5newC5pQHUEQBKkkpyrXbfeaf4Ll0KFSYinM9KhgSoWUTINMzF6DBkv4V791ZOUCay9tN1QO9lbi6e369j/b8/479kxvV/0VozUhQBKRtOf4c7/13GCZ3DqauHLajkVMhcbVch7bIKS32zOx/YEFCAuBNOYZuegq21dyudk2QQRGDQSOoDx1e+m92XYGMbHUJ1Vnjj79gcNnDvVrDYcZiEBp7CyE26+q/eZfH5jcQpOb6T/+9f5tL/HbiyEpIgI2XGvoM08NP/2Bw92LUp/QJjIio7Zv3HvJZwrlidgstssOFb7jOKcE6q0fvrRqROzCfRLrov0UO0bLjXT+B1DKh7bl8ZPdFIEooMQIoDTXesd+MvjMh56+cHhYb7psWGmKQ+n3hj/zzg2//PFdtTUcDmOOuDZJ175h/aATP//IEpHSLokRt6YWTwZP3rew/Yrmxj2NcBgJKyCrWWvcxu2CU5gdcZtOVjLVtum3zWhlFaukiXlLQUWGFtoxE2AxaNblUD0s0Ip4K6fAW98yIyDXWwrZue/z57/we0d7i1BraRAQhm4nmNjgvP33dt7665uNhCYAUoBEHAG5/JLXzcxuqR17srN8MXBcRYiOh91F+cndC4pg51VrvAZEYZw2tNgnZ42+7AAOK8GpFCzDvFpg+aIxHrW6x3GEp5XUdcyu37JBycWAWedBntmwAIjS5DU0h/DsP/e+89lTLzzcqbdcpTEayrAX1abourfM3vy+jdOb3e5KQIBARRAsIsLYnPQWz0T3fPbcv359btDmWs1xfIyNDDrRzpc1b3nftn0/WydHgr4xydaorFc6DYczQtOOU4Qqo0shan5cgcX9MaGOAALe+fwtq3DRuqmUa48iMa8moLCI3QQuAsREpLVSPhFgb9489+OVB/7h4pF/baOQ6zlBP4oi09qgr7557Q3v2LDtCj8YBmFASlV2oKXvjQHHZ9/3Tx3q3//FC4/fs7Bynh0HvYYOAwPAl7584sZfnN1z3URzRjFwFHAcsbCIIBTHZQigEFLa112KLnKfnpgjBsjyiGIprXIAmwBAtsexqgH28fkVDNqOMot6mMSAclg7rnaEks4TAhFkA2FfVubC08/2n3tw5dmH2ueP9jkCx9GMXG/R5isaB149feWrJ2Z3eHFggr6gwiLLKU64S1VEkrOhWNw6Oa66eCw6eO/yk/cunTnU63cNCUWhIQc2XFrb+4rJPTe0Nu+rT866boMUAQCKoDALmzjEKIo4SspLIpA3p5UTTcm6hFMFlfRXC4+pL01ROcY6jw3ZR2o+SVGTobsYL13gQVtWFgIxYa+N8UC6C8Pl89H86WDhTNhdjhCw3lQTM87kBnf9pf6Oq1o7r25t2OVpH8IBRwFnBzDn5NqJf249cqayCDgeejUVDnHuaHD00c6xJ1cuPB+1zwUrC2G/EwtAc8pZu9mZ2eJPbnBbM772pT5B6DiT0259EiY36Na0Tk/fK8yXVDWyMAwwnpXjbKWkfgrtv+zyU8tBiIjGcNCVlfmwfZHbF8LuYhAHEA1FOVBvuf4Etaa95lo1uU5PTntuK6lNxGHAwoSEpdSvYKWUYFLY4jS5Tra9IInra+2RGAg73F6I2hdNdzHuLITDFdNfCeMYXF9pT5rT7uR6d3KWJmZqXhOUwvwUmOrfkBjFT/GeLBbLi7LSruWNAa1tOy3pIaJCrUFpRQqIMPU72R4TZmBmE7OJgA0mFVIkzHu3Ux4VId5IBSQHpjWHXPMl2b+hQWtUGkkBpa3dqfsBEGZhhjhmE7GYhIMvkvLhSLyZOYwRVuoRM2kHgPnIVB2oxHeBtK8GJZYwBpBYkhIY2kc9ZoE5AiIoJVIccZTFnvbfyqv++Yus5AwyYtklKXpQUvlnNKHEAYsggEndNabJL0J69B7mJ5tC+bDWUjI+wpjqr8WNo6wcyV6rEMeRKzPuIoAkPd1QDuvtFLMweFahID9KseLTyk8vDj5d9aiLJI6AxOKkV9tL0JUmyBEMolTVufLOPle2TMPoSjdmn9vVRnlRHbeeVwB+9VS8GEEqLB155dVGGfO0USau9sixd8rYB4+92567rHb9/wfMDBVr/29engAAACV0RVh0ZGF0ZTpjcmVhdGUAMjAxMS0wOS0wNlQwODoyNzozNy0wNTowMKqflKEAAAAldEVYdGRhdGU6bW9kaWZ5ADIwMTEtMDktMDZUMDg6Mjc6MzctMDU6MDDbwiwdAAAAAElFTkSuQmCC";
	endif;
	return($img);
}

function _getUserNameByID($uuid)
{
	$CI = get_session();
	
	$sql = "SELECT Firstname,Lastname FROM UserAccounts WHERE PrincipalID = ?";
	$sqlargs = array($uuid);
	$query = $CI->db->query($sql,$sqlargs);
	$data = $query->row_array();
	if(count($data) > 0):
		$name = $data['Firstname'] . " " . $data['Lastname'];
	else:
	$name = 'NotFound: ' . $uuid;
	endif;
	return($name);
}

function _saveProfileThumb($image,$uuid)
{
	$CI = get_instance();
	$image->setImageFormat('png');
	$pngblob = $image->getImageBlob();
	$encpngblob = base64_encode($pngblob);
	
	$sql = "UPDATE accountreqs SET profileImgThumb = ? WHERE uuid = ?";
	$sqlargs = array(
		$encpngblob,
		$uuid
	);
	$query = $CI->db->query($sql,$sqlargs);
	return($query);
}

function echopng($imgresource)
{
	echo "<img src='data:image/png;base64," . $imgresource . "'>";
}

function _logfilter($logentry) {
	if ( ! write_file('application/logs/egress.log', $logentry,'a+')):
		echo 'Unable to write the logfile';
		return(FALSE);
	else:
		return(TRUE);
	endif;
}

function _newavatar() {
	$CI = get_session();

	if(check_session() == FALSE):
    	$login_data = array(
					      'session_status' => FALSE,
					      'firstname' => '',
					      'lastname' => '',
					      'userlevel' => '',
					      'passwordhash' => '',
					      'salt' => '',
					      'UUID' => ''
                      );
        $CI->session->set_userdata($login_data);
        redirect('splash');
    else:
		$login_data = session_get('login_data');
		if ($login_data['userlevel'] < 250): // test presence of valid creds in session data
			redirect('splash');
		endif;
	endif;

	$request_data['avname_first'] = $CI->input->post('firstname',TRUE);
	$request_data['avname_last'] = $CI->input->post('lastname',TRUE);
	$request_data['email'] = $CI->input->post('cemail',TRUE);
	$request_data['RL_First'] = $CI->input->post('realfirstname',TRUE);
	$request_data['RL_Last'] = $CI->input->post('reallastname',TRUE);
	$request_data['usertitle'] = $CI->input->post('usertitle',TRUE);
	$request_data['userlevel'] = $CI->input->post('userlvl',TRUE);
	$request_data['userflags'] = $CI->input->post('userflags',TRUE);

	$timenow = time();
	$uuid = _uuid();

	$pass1 = $CI->input->post('cpassword');
	$pass2 = $CI->input->post('crepassword');

	if(($pass1 !== $pass2)||($pass1 == NULL)):
		redirect('accountreq');
	else:
		$sql      = "SELECT * FROM UserAccounts WHERE FirstName = '" . $request_data['avname_first'] . "' and LastName = '" . $request_data['avname_last'] . "';";
		$query  = $CI->db->query($sql);
		
		if($query->num_rows() > 0):
	       		$message = array('coarsemsg' => 'The requested avatar name already exists in the grid user tables.',
                                             'finemsg' => 'Someone else has already taken this avatar name.',
                                             'callback' => 'splash');
			_confirm($message);
			return;
		endif;
		
		$salted = sprintf('%s', md5(_uuid()));
		$hash = md5(md5($pass1) . ":" . $salted);

		$sql = "INSERT INTO accountreqs (uuid,chronostamp,avFirst,avLast,Email,RLfirst,RLlast,passwordhash,notes,salt,status) VALUES (?,?,?,?,?,?,?,?,?,?,?);";
		$sqlargs = array(
			$uuid,
			$timenow,
			$request_data['avname_first'],
			$request_data['avname_last'],
			$request_data['email'],
			$request_data['RL_First'],
			$request_data['RL_Last'],
			$hash,
			'',
			$salted,
			'established'
		);
		$query = $CI->db->query($sql,$sqlargs);
		if($query !== TRUE):
			$message = array('coarsemsg' => 'Error inserting into table accountreqs during SQL query execution.',
				       'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?',
				       'callback' => 'splash'
					);
			_confirm($message);
		else:
		
			// now create the actual user account
			$inv_template = array('Calling Cards' =>  2,
					      'Objects' =>  6,
					      'Landmarks' =>  3,
					      'Clothing' =>  5,
					      'Gestures' => 21,
					      'Body Parts' => 13,
					      'Textures' =>  0,
					      'Scripts' => 10,
					      'Photo Album' => 15,
					      'Lost And Found' => 16,
					      'Trash' => 14,
					      'Notecards' =>  7,
					      'My Inventory' =>  9,
					      'Sounds' =>  1,
					      'Animations' => 20
			);

			$newuser_data = array(
				'PrincipalID' => $uuid,
				'passwordSalt' => $salted,
				'passwordHash' => $hash,
				'FirstName' => $request_data['avname_first'],
				'LastName' => $request_data['avname_last'],
				'Email' => $request_data['email'],
				'UserLevel' => $request_data['userlevel'],
				'UserFlags' => $request_data['userflags'],
				'UserTitle' => $request_data['usertitle'],
				'ScopeID' => '00000000-0000-0000-0000-000000000000'
				);

			$timenow = time();

			// write UserAccounts table particulars

			$query = $CI->db->query("INSERT INTO UserAccounts (PrincipalID,FirstName,LastName,ScopeID,Email,UserLevel,UserFlags,UserTitle,ServiceURLS,Created) VALUES ('".$newuser_data['PrincipalID']."','".$newuser_data['FirstName']."','".$newuser_data['LastName']."','".$newuser_data['ScopeID']."','".$newuser_data['Email']."',".$newuser_data['UserLevel'].",".$newuser_data['UserFlags'].",'".$newuser_data['UserTitle']."','HomeURI= GatekeeperURI= InventoryServerURI= AssetServerURI= ',".$timenow.");");
			if($query === FALSE):
				$message = array('coarsemsg' => 'Error inserting into table UserAccounts during SQL query execution.',
						'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?',
						'callback' => 'splash'
						);
				_confirm($message);
			else:
				// write auth table particulars

				$query = $CI->db->query("INSERT INTO auth (UUID,passwordHash,passwordSalt,webLoginKey) VALUES ('".$newuser_data['PrincipalID']."','".	$newuser_data['passwordHash']."','".$newuser_data['passwordSalt']."','00000000-0000-0000-0000-000000000000');");
				if($query === FALSE):
					$errmsg = array('callback' => 'splash',
							'coarsemsg' => 'Error inserting into table auth during SQL query execution.',
							'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?'
							);
					$CI->load->view('confirm',$errmsg);
				else:
					// this is where the avatar's inventory folders are created.
					// $inv_masterID is the uuid of the 'My Inventory' folder, the parent folder of all other folders, and which has a
					// folder UUID of UUID.zero
					// all other folders have a unique, random UUID for a folder ID, and the folder ID of the 'My Inventory' folder for their
					// parent folder ID.
	
					$inv_masterID = _uuid();

					foreach ($inv_template as $invfldr_name => $inv_type) {

						if($inv_type === 9):
							$invfldr_uuid = $inv_masterID;
							$inv_parent = '00000000-0000-0000-0000-000000000000';
						else:
							$invfldr_uuid = _uuid();
							$inv_parent = $inv_masterID;
						endif;
						$query = $CI->db->query("INSERT INTO inventoryfolders (folderName,type,version,folderID,agentID,parentFolderID) VALUES ('".$invfldr_name."','".$inv_type."','1','".$invfldr_uuid."','".$newuser_data['PrincipalID']."','".$inv_parent."');");
					}

					if($query === FALSE):
						$callback = array('callback' => 'splash',
										  'coarsemsg' => 'Error inserting into table inventoryfolders during SQL query execution.',
										  'finemsg' => 'This is indicative of an underlying operating system or filesystem issue -- have you an impacted disk?'
								);
						_confirm($callback);
					else:
						$callback = array('callback' => 'splash',
										  'coarsemsg' => 'Account Creation Successful.',
										  'finemsg' => 'The account has been created and is ready for use.'
								);
						_confirm($callback);
					endif;
				endif;
			endif;
		endif;
	endif;
}
?>
