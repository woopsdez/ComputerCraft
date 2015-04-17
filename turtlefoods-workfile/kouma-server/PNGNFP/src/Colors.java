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

public enum Colors {
	// @formatter:off
	WHITE		('0',	240,	240,	240),
	ORANGE		('1',	235,	136,	68),
	MAGENTA		('2',	195,	84,		205),
	LIGHT_BLUE	('3',	102,	137,	211),
	YELLOW		('4',	222,	207,	42),
	LIME		('5',	65,		205,	52),
	PINK		('6',	216,	129,	152),
	GRAY		('7',	67,		67,		67),
	LIGHT_GRAY	('8',	153,	153,	153),
	CYAN		('9',	40,		118,	151),
	PURPLE		('a',	123,	47,		190),
	BLUE		('b',	37,		49,		146),
	BROWN		('c',	81,		48,		26),
	GREEN		('d',	59,		81,		26),
	RED			('e',	179,	49,		44),
	BLACK		('f',	0,		0,		0);
	// @formatter:on

	private final char hex;
	private final int red;
	private final int green;
	private final int blue;

	Colors(char hex, int red, int green, int blue) {
		this.hex = hex;
		this.red = red;
		this.green = green;
		this.blue = blue;
	}

	public char getHex() {
		return hex;
	}

	public int getRed() {
		return red;
	}

	public int getGreen() {
		return green;
	}

	public int getBlue() {
		return blue;
	}
}