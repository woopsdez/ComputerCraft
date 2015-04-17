import java.awt.Color;

public class ColorChange {
	public static double compand(double V) {
		if (V <= 0.04045)
			return V / 12.92;
		else
			return Math.pow((V + 0.055) / 1.055, 2.4);
	}

	// V = [0.0, 1.0]
	public static double[] RGBtoXYZ(double[] V) {
		double[] v = new double[3];
		v[0] = compand(V[0]);
		v[1] = compand(V[1]);
		v[2] = compand(V[2]);

		double[] XYZ = new double[3];
		XYZ[0] += v[0] * 0.4124564;
		XYZ[0] += v[1] * 0.3575761;
		XYZ[0] += v[2] * 0.1804375;

		XYZ[1] += v[0] * 0.2126729;
		XYZ[1] += v[1] * 0.7151522;
		XYZ[1] += v[2] * 0.0721750;

		XYZ[2] += v[0] * 0.0193339;
		XYZ[2] += v[1] * 0.1191920;
		XYZ[2] += v[2] * 0.9503041;

		return XYZ;
	}

	public final static double[] D65 = { 95.047, 100.00, 108.883 };
	public final static double e = 0.008856;
	public final static double k = 903.3;

	public static double calcF(double r) {
		if (r > e)
			return Math.cbrt(r);
		else
			return (k * r + 16) / 116;
	}

	public static double[] XYZtoLab(double[] XYZ) {
		double x = XYZ[0] / D65[0];
		double y = XYZ[1] / D65[1];
		double z = XYZ[2] / D65[2];

		double[] f = new double[3];
		f[0] = calcF(x);
		f[1] = calcF(y);
		f[2] = calcF(z);

		double[] Lab = new double[3];
		Lab[0] = 116 * f[1] - 16;
		Lab[1] = 500 * (f[0] - f[1]);
		Lab[2] = 200 * (f[1] - f[2]);

		return Lab;
	}

	public static double[] RGBtoLab(Color c) {
		double[] V = new double[3];
		V[0] = c.getRed() / 255.0;
		V[1] = c.getGreen() / 255.0;
		V[2] = c.getBlue() / 255.0;

		double[] XYZ = RGBtoXYZ(V);
		double[] Lab = XYZtoLab(XYZ);

		return Lab;
	}

	public final static double SL = 1;
	public final static double KL = 1;
	public final static double KC = 1;
	public final static double KH = 1;
	public final static double K1 = 0.045;
	public final static double K2 = 0.015;

	public static double deltaE(double[] Lab2, double[] Lab1) {
		double DL = Lab1[0] - Lab2[0];
		double C1 = Math.sqrt(Lab1[1] * Lab1[1] + Lab1[2] * Lab1[2]);
		double C2 = Math.sqrt(Lab2[1] * Lab2[1] + Lab2[2] * Lab2[2]);
		double DC = C1 - C2;
		double Da = Lab1[1] - Lab2[1];
		double Db = Lab1[2] - Lab2[2];
		double DH = Math.sqrt(Da * Da + Db * Db - DC * DC);
		double SC = 1 + K1 * C1;
		double SH = 1 + K2 * C1;

		double p1 = DL / (KL * SL);
		double p2 = DC / (KC * SC);
		double p3 = DH / (KH * SH);

		double DE = Math.sqrt(p1 * p1 + p2 * p2 + p3 * p3);

		return DE;
	}
}
