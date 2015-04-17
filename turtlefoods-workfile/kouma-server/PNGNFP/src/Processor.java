/*
    PNGNFP - The ComputerCraft Image Converter
    Copyright (C) 2013  Yingtong Li (RunasSudo)

    PNGNFP is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PNGNFP is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PNGNFP.  If not, see <http://www.gnu.org/licenses/>.
 */

import java.awt.Color;
import java.awt.image.BufferedImage;
import java.io.File;
import java.io.PrintStream;
import java.util.HashMap;

import javax.imageio.ImageIO;

public class Processor {
	public static void convert(HashMap<String, String> opts) throws Exception {
		System.out.println("Output format: " + opts.get("target"));
		System.out.println("Input file:    " + opts.get("inputFile"));
		System.out.println("Output file:   " + opts.get("outputFile"));
		System.out.println("===========================");
		if (opts.get("target").equalsIgnoreCase("nfp")) {
			convertNFP(opts);
		}
	}

	public static void convertNFP(HashMap<String, String> opts)
			throws Exception {
		PrintStream output = new PrintStream(new File(opts.get("outputFile")));

		int pixelWidth = Integer.parseInt(opts.get("pixelWidth"));
		int pixelHeight = Integer.parseInt(opts.get("pixelHeight"));

		BufferedImage inImage = ImageIO.read(new File(opts.get("inputFile")));
		for (int y = 0; y < inImage.getHeight(); y += pixelHeight) {
			for (int x = 0; x < inImage.getWidth(); x += pixelWidth) {
				Color pixel = interpolate(opts, inImage, x, y, pixelWidth,
						pixelHeight);

				Colors closest = null;
				double bestFit = Double.MAX_VALUE;
				for (Colors c : Colors.values()) {
					Color cc = new Color(c.getRed(), c.getGreen(), c.getBlue());
					double dist = distance(opts, pixel, cc);
					if (dist < bestFit) {
						closest = c;
						bestFit = dist;
					}
				}
				output.print(closest.getHex());
			}
			output.println();
		}

		output.close();
		System.out.println("Conversion successful");
	}

	public static double distance(HashMap<String, String> opts, Color pixel,
			Color cc) {
		if (opts.get("distance").equalsIgnoreCase("cie94")) {
			double[] pixelL = ColorChange.RGBtoLab(pixel);
			double[] cL = ColorChange.RGBtoLab(cc);
			return ColorChange.deltaE(pixelL, cL);
		}
		if (opts.get("distance").equalsIgnoreCase("euclidean")) {
			double dR = pixel.getRed() - cc.getRed();
			double dG = pixel.getGreen() - cc.getGreen();
			double dB = pixel.getBlue() - cc.getBlue();

			return dR * dR + dG * dG + dB * dB;
		}
		return Double.MAX_VALUE;
	}

	public static Color interpolate(HashMap<String, String> opts,
			BufferedImage inImage, int x, int y, int pixelWidth, int pixelHeight) {
		if (opts.get("interpolation").equalsIgnoreCase("corners")) {
			return interpolateCorners(inImage, x, y, pixelWidth, pixelHeight);
		}
		if (opts.get("interpolation").equalsIgnoreCase("pixel")) {
			return interpolatePixel(inImage, x, y, pixelWidth, pixelHeight);
		}
		if (opts.get("interpolation").equalsIgnoreCase("topLeft")) {
			return new Color(inImage.getRGB(x, y));
		}
		return null;
	}

	public static Color interpolateCorners(BufferedImage inImage, int x, int y,
			int pixelWidth, int pixelHeight) {
		int xmax = (x + pixelWidth - 1 < inImage.getWidth()) ? x + pixelWidth
				- 1 : inImage.getWidth() - 1;
		int ymax = (y + pixelHeight - 1 < inImage.getHeight()) ? y
				+ pixelHeight - 1 : inImage.getHeight() - 1;

		Color tl = new Color(inImage.getRGB(x, y));
		Color tr = new Color(inImage.getRGB(xmax, y));
		Color bl = new Color(inImage.getRGB(x, ymax));
		Color br = new Color(inImage.getRGB(xmax, ymax));

		int red = (tl.getRed() + tr.getRed() + bl.getRed() + br.getRed()) / 4;
		int green = (tl.getGreen() + tr.getGreen() + bl.getGreen() + br
				.getGreen()) / 4;
		int blue = (tl.getBlue() + tr.getBlue() + bl.getBlue() + br.getBlue()) / 4;

		return new Color(red, green, blue);
	}

	public static Color interpolatePixel(BufferedImage inImage, int x, int y,
			int pixelWidth, int pixelHeight) {
		int xmax = (x + pixelWidth - 1 < inImage.getWidth()) ? x + pixelWidth
				- 1 : inImage.getWidth() - 1;
		int ymax = (y + pixelHeight - 1 < inImage.getHeight()) ? y
				+ pixelHeight - 1 : inImage.getHeight() - 1;

		int rsum = 0, gsum = 0, bsum = 0;
		int pixels = 0;
		for (int i = 0; i <= xmax; i++) {
			for (int j = 0; j <= ymax; j++) {
				Color c = new Color(inImage.getRGB(i, j));
				rsum += c.getRed();
				gsum += c.getGreen();
				bsum += c.getBlue();
				pixels++;
			}
		}

		return new Color(rsum / pixels, gsum / pixels, bsum / pixels);
	}
}
