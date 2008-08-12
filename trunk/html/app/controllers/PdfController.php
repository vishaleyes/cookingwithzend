<?php

class PdfController extends DefaultController  
{
	public function generateAction()
	{
		
		// Create new PDF document.
		$pdf = new Zend_Pdf();
		// 421 x 596 = A5 Landscape in pixels @ 72dpi
		$pdf->pages[] = new Zend_Pdf_Page(596, 421);
		$pdf->pages[] = new Zend_Pdf_Page(596, 421);
		
		$front = $pdf->pages[0];
		$back = $pdf->pages[1];
		
		// Create new font
		$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

		// Apply font
		$front->setFont($font, 18);

		// Start at the top
		$y = 590;
		// With a left Margin of 10
		$x = 10;

		$front->drawText( $this->recipe->name, $x, $y );
		
		$y = $y - 30;
		$front->setFont($font, 12);
		$front->drawText( 'Difficulty: ' . $this->recipe->difficulty, $x, $y );
		$y = $y - 15;
		$front->drawText( 'Preparation Time: ' . $this->recipe->preparation_time, $x, $y );
		$y = $y - 15;
		$front->drawText( 'Cooking Time: ' . $this->recipe->preparation_time, $x, $y );
		$y = $y - 15;
		$front->drawText( 'Serves: ' . $this->recipe->serves, $x, $y );
		$y = $y - 15;
		$front->drawText( 'Freezable: ' . $this->recipe->freezable, $x, $y );
		
		$ingredients = $this->recipe->findRecipeIngredient();
		$y = $y - 15;
		
		foreach ($ingredients as $ingredient) {
			$y = $y - 15;
			$text = '';
			if ( $ingredient->quantity > 0 )
				$text .= $ingredient->quantity;

			if ( $ingredient->amount > 0 )
				$text .= $ingredient->amount . ' ';

			if ( !empty( $ingredient->measurement ) )
				$text .= $ingredient->measurement_abbr . ' ';

			$text .= $ingredient->name;
			$front->drawText( $text, $x, $y );
		}
		
		$back->setFont($font, 18);
		
		$pdf->save('pdf/foo.pdf');

	}
	
	public function postDispatch() {
		exit;
	}
	
}
