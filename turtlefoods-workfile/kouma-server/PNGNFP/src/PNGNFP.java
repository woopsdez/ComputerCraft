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

import java.util.HashMap;

public class PNGNFP {
	public static void main(String[] args) {
		try {
			String options = args[0];
			HashMap<String, String> opts = new HashMap<String, String>();

			if (options.equalsIgnoreCase("--help")) {
				System.out.println("PNGNFP v0.1b2 by RunasSudo");
				System.out.println("Licensed under GPLv3. See COPYING.txt");
				System.out.println();
				System.out.println("===== USAGE =====");
				System.out
						.println("java -jar PNGNFP.jar [OPTIONS] [ARGUMENTS]");
				System.out
						.println("Arguments are supplied in order of options given");
				System.out.println();
				System.out.println("===== OPTIONS =====");
				// System.out.println("c   Convert image");
				System.out.println("s   Use a pixel size other than 1x1");
				System.out.println("    Takes arguments [width] [height]");
				System.out.println("v   Corner-average interpolation");
				System.out.println("w   Pixel-average interpolation");
				System.out.println("d   Use Delta-E color matching");
				System.out.println("n   Outputs an NFP file");
				System.out.println("    Takes arguments [outfile]");
				System.out.println("f   Use a file as input");
				System.out.println("    Takes arguments [file]");
				System.out.println();
				System.out.println("===== EXAMPLE =====");
				System.out
						.println("java -jar PNGNFP.jar snf 12 18 pic.nfp Tux.png");
				System.out
						.println("Converts Tux.png to NFP format. Outputs to pic.nfp. Uses pixel size 12x18.");
				System.exit(0);
			}

			// Defaults
			opts.put("pixelWidth", "1");
			opts.put("pixelHeight", "1");
			opts.put("interpolation", "topLeft");
			opts.put("distance", "euclidean");

			int index = 1;
			for (int i = 0; i < options.length(); i++) {
				char c = options.charAt(i);

				switch (c) {
				case 's':
					opts.put("pixelWidth", args[index++]);
					opts.put("pixelHeight", args[index++]);
					break;
				case 'v':
					opts.put("interpolation", "corners");
					break;
				case 'p':
					opts.put("interpolation", "pixel");
					break;
				case 'd':
					opts.put("distance", "cie94");
					break;
				case 'n':
					opts.put("target", "nfp");
					opts.put("outputFile", args[index++]);
					break;
				case 'f':
					opts.put("inputFile", args[index++]);
					break;
				default:
					System.err.println("Unknown option " + c);
					System.exit(1);
				}
			}

			Processor.convert(opts);
		} catch (NullPointerException | ArrayIndexOutOfBoundsException e) {
			System.err.println("Too few paramenters. (Probably)");
			e.printStackTrace();
			System.exit(1);
		} catch (Exception e) {
			System.err
					.println("Error - show the following information to RunasSudo:");
			e.printStackTrace();
			System.exit(1);
		}
	}
}
