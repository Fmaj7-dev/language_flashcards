#!/usr/bin/python

import random
import math
import matplotlib.pyplot as plt

lam = 0.05
domain = 100
num_samples = 1000000

#fill array
keys = []
values = [0]*domain
for i in range(0, domain):
    keys.append(i)

#perform rand
for i in range(1, num_samples):
    rand_value = random.random()
    actual_value = -math.log(1-rand_value)/lam
    int_value = round(actual_value-0.5)
    if (int_value < domain):
        values[int (int_value)] += 1

plt.plot(keys, values)
plt.ylabel('PDF')
plt.show()
