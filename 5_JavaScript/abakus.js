window.onload = function() {
			var beads = document.querySelectorAll('div.bead');
			for(i=0;i<beads.length;i++){
				beads[i].onclick = function(){
					stiil = window.getComputedStyle(this);
					console.log(stiil.getPropertyValue('float'));
					if(stiil.getPropertyValue('float') === 'left'){
						this.style.cssFloat = 'right';
					}
					else{
						this.style.cssFloat = 'left';
					}
				}
			}
			
		}