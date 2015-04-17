import java.awt.Color;

public class ColorDiffTest {
	public static void main(String[] args) {
		Color c1 = new Color(Integer.parseInt(args[0]),
				Integer.parseInt(args[1]), Integer.parseInt(args[2]));
		Color c2 = new Color(Integer.parseInt(args[3]),
				Integer.parseInt(args[4]), Integer.parseInt(args[5]));

		double[] l1 = ColorChange.RGBtoLab(c1);
		double[] l2 = ColorChange.RGBtoLab(c2);
		double deltaE = ColorChange.deltaE(l2, l1);
		
		System.out.println(deltaE);
	}
}
